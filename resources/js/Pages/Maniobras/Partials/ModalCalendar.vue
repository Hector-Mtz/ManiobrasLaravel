<script setup>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import DialogModal from "@/Components/DialogModal2.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import BtnCloseCalendar from "@/Components/BtnCloseCalendar.vue";
import BtnCalendar from "@/Components/BtnCalendar.vue";
import TableManiobristas from "./TableManiobristas.vue";
import DataTable from "@/Components/DataTable.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import "v-calendar/dist/style.css";
import { SetupCalendar, Calendar, DatePicker } from "v-calendar";
import ButtonClose from "@/Components/ButtonClose.vue";

var props = defineProps({
    turnos: Object,
    maniobra_id: Number,
    maniobristas: Object,
    arregloCalendar: Object,
    show: Boolean
});


const emit = defineEmits(["close"]);

const close = () => {
    emit("close");
};

let date = ref(null);


const attributes = computed(() => {
    let  attributesAux = [];
    for (let index = 0; index < props.arregloCalendar.length; index++) {
    const element = props.arregloCalendar[index];
    let total = 0;

    if (element.lista_asistencia && element.cant_asistencia) {
        total = element.cant_asistencia - element.lista_asistencia;
        if (total > 0) {
            let formatDate = new Date(element.fecha);
            const dia = formatDate.getDate() + 1;
            const mes = formatDate.getMonth();
            const año = formatDate.getFullYear();

            //console.log(año +'-'+mes+'-'+dia);

              attributesAux.push(
                {
                    dates: new Date(año, mes, dia),
                    highlight: 'red'
                }
            );
        }
        if (total <= 0) {
            let formatDate = new Date(element.fecha);
            const dia = formatDate.getDate() + 1;
            const mes = formatDate.getMonth();
            const año = formatDate.getFullYear();

            attributesAux.push(
                {
                    dates: new Date(año, mes, dia),
                    highlight: 'purple'
                }
            );
        }
    }

}

return attributesAux;
});

//console.log(props.arregloCalendar)


//console.log(attributes.value)


let turnoSelect = ref(null);
const selectedTurn = (turno) => {
    //console.log(turno);
    turnoSelect.value = turno.id;

    if (date.value != null) {
        let formatDate = new Date(date.value);
        let dia = formatDate.getDate();
        let mes = (formatDate.getMonth() + 1);
        const año = formatDate.getFullYear();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;

        let mesToString =mes.toString();
        let diaToString = dia.toString();

        let cero = "0"; 
        let newMes = "";
        let newDia = "";
        let fechaCompleta = "";

        if(mesToString.length < 2)
        {
             //console.log(cero+mesToString)
             newMes =  cero+mesToString;

             if(diaToString.length < 2)
             {
                newDia = cero+diaToString;
                fechaCompleta = año+ '-' + newMes + '-' +newDia

             }
             else
             {
              fechaCompleta = año + '-' + newMes + '-' + dia; 
             }
        }
        else
        {
            if(diaToString.length < 2)
             {
                newDia = cero+diaToString;
                fechaCompleta = año+ '-' + mes + '-' +newDia

             }
             else
             {
              fechaCompleta = año + '-' + mes + '-' + dia; 
             } 
        }

   
       
        Inertia.visit(route("maniobras"), {
            data: {
                turno_id: turnoSelect.value,
                fecha: fechaCompleta,
            },
            preserveState: true,
            preserveScroll: true,
            only: ["maniobristas"],
        });
    }
};
</script>
<template>
    <DialogModal :show="show" @close="close()">
        <template #title> </template>
        <template #content>
            <div class="grid-cols-3 grid-rows-2 m-2 sm:grid w-80 sm:w-full sm:grid-cols-2 -mb-96">
                <div class="border-r-0 border-none sm:border-r-4 sm:border-solid modal_scroll">
                    <div class="hidden sm:grid">
                        <h3 class="mb-2 ml-56 text-xl font-bold text-blue-900 sm:mr-0">
                            Calendario
                        </h3>
                    </div>

                    <div class="hidden mr-10 sm:grid">
                        <DatePicker is-expanded :rows="12" :attributes="attributes" v-model="date" />
                    </div>

                    <!--BOTONES VERSIÓN MOVIL-->
                    <div class="grid grid-cols-3 place-items-center sm:hidden">
                        <BtnCalendar class="p-1 text-xs" v-for="turno in turnos" :key="turno.id"
                            @click="selectedTurn(turno)">{{ turno.name }}
                        </BtnCalendar>
                    </div>

                    <!--CALENDARIO VERSIÓN MOVIL-->
                    <div class="mt-10 ml-16 -mb-96 sm:hidden">
                        <DatePicker :rows="1" v-model="date" />
                    </div>
                </div>

                <!--TABLA VERSIÓN MOVIL-->
                <div class="-mb-96 sm:hidden modal_scroll">
                    <div class="mt-10 ml-9 -mb-96 sm:hidden">
                        <div>
                            <TableManiobristas :maniobristas="maniobristas" v-if="date && turnoSelect" :date="date"
                                :turno="turnoSelect" class="-ml-12 " style="width: 395px;">
                            </TableManiobristas>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="hidden sm:grid">
                        <div class="grid items-center justify-center grid-cols-4 gap-4 text-center">
                            <BtnCalendar class="p-4" v-for="turno in turnos" :key="turno.id"
                                @click="selectedTurn(turno)">{{ turno.name }}</BtnCalendar>
                        </div>
                        <div>
                            <TableManiobristas :maniobristas="maniobristas" v-if="date && turnoSelect" :date="date"
                                :turno="turnoSelect"></TableManiobristas>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <BtnCloseCalendar @click="close()" style="float: right">
                Cerrar
            </BtnCloseCalendar>
        </template>
    </DialogModal>
</template>
