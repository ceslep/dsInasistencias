<script>
    import { createEventDispatcher } from "svelte";
    import {
        Button,
        Modal,
        ModalBody,
        ModalFooter,
        ModalHeader,
        Table,
        Alert,
        Spinner,
    } from "sveltestrap";
    import { DoorClosed, Trash } from "svelte-bootstrap-icons";
    import { urlPhp, iColegio } from "../Stores";
    import ModalReportePeriodo from "./ModalReportePeriodo.svelte";

    export let open = false;
    let openReporte = false;
    export let estudianteResumen;
    let inasistencias;
    let totalInasistencias;
    let inasistenciasMateria;
    let columnas;
    let filas;
    let datosResumen = [];
    let calculando = false;
    let noexisten=false;
    const toggle = () => (open = !open);

    const dispatch = createEventDispatcher();
    const closeModal = () => {
        
        dispatch("close", {
            data: "close",
        });
    };

    const openingModal = async () => {
        calculando = true;
        noexisten=false;
        inasistencias=[];
        totalInasistencias=[];
        let response = await fetch($urlPhp + "obtenerInasistencias.php", {
            method: "POST",
            body: JSON.stringify(estudianteResumen),
            headers: { "Content-Type": "application/json" },
            mode: "cors",
        });
        datosResumen = await response.json();
        inasistencias = datosResumen.resumen;
        totalInasistencias = datosResumen.totalInasistencias;
        calculando = false;
        if (totalInasistencias.length==0) noexisten=true;
    };

    const closeModalReporte = (e) => {
        openReporte = false;
        console.log(e.detail.data);
        if (e.detail.data.refresh) openingModal();
    };

    $: if (inasistencias) {
        columnas = inasistencias.map((inasistencia) => {
            return inasistencia.periodo;
        });
        columnas = columnas.filter((valor, indice) => {
            return columnas.indexOf(valor) === indice;
        });
    }

    $: if (inasistencias) {
        filas = inasistencias.map((inasistencia) => {
            return inasistencia.imateria;
        });
        filas = filas.filter((valor, indice) => {
            return filas.indexOf(valor) === indice;
        });
    }

    const valueOf = (materia, periodo) => {
        let horas = inasistencias.filter((inasistencia) => {
            return (
                inasistencia.imateria === materia &&
                inasistencia.periodo === periodo
            );
        }).map(inasistencia=>{
            return parseInt(inasistencia.horas);
        });
        if (horas.length > 0) return horas.reduce((total,hora)=>{return total+hora});
        else return "0";
    };

    const htmlInasistencia = (materia, periodo) => {
        let inasist = totalInasistencias.filter((inasistencia) => {
            return (
                inasistencia.imateria === materia &&
                inasistencia.periodo === periodo
            );
        });
        let html = "<table class='table'>";

        inasist.forEach((inas) => {
            html += `
            <tr>
                    <td class='text-light align-middle'>
                        ${inas.fecha.substring(5, 10)}
                    </td>
                    <td class='' style='color:yellow;'>
                        ${inas.horas} Horas
                    </td>
                    <td class="${
                        inas.excusa == "t" ? "text-success" : "text-light"
                    }">
                        ${inas.excusa == "t" ? "Excusa" : "Sin excusa"}
                    </td>
                    
            </tr>
           `;
        });
        html += "</table>";

        return html;
    };

    const Total = (materia) => {
        return inasistencias
            .map((inasistencia) => {
                if (inasistencia.imateria === materia) {
                    if (inasistencia.horas) {
                        return parseInt(inasistencia.horas);
                    }
                } else return 0;
            })
            .reduce((T, horas) => {
                return T + horas;
            });
    };

    const openMR = (materia, periodo) => {
        inasistenciasMateria = totalInasistencias.filter((ti) => {
            return ti.imateria === materia && ti.periodo === periodo;
        });
        if (inasistenciasMateria.length > 0) openReporte = true;
    };
</script>

<Modal
    isOpen={open}
    {toggle}
    class="modal-dialog modal-dialog-centered"
    on:close={closeModal}
    on:opening={openingModal}
>
    <ModalHeader class="bg-success p-2 text-white bg-opacity-10" {toggle}
        >Resumen Inasistencias
    </ModalHeader>
    <ModalBody>
        {#if calculando}
            <div class="d-flex justify-content-center">
                <Spinner color="success" style="width: 3rem; height: 3rem;" />
            </div>
        {/if}
        {#if datosResumen.totalInasistencias}
            {#if datosResumen.totalInasistencias.length > 0}
                <Table bordered hover>
                    <thead>
                        <th class="text-center">Materias</th>
                        {#if columnas}
                            {#each columnas as periodo}
                                <th class="text-center text-success"
                                    >{periodo}</th
                                >
                            {/each}
                            <th class="text-center text-primary"> TOTAL </th>
                        {/if}
                    </thead>
                    <tbody>
                        {#if filas}
                            {#each filas as materia, i}
                                <tr>
                                    <th scope="row">{materia}</th>
                                    {#each columnas as periodo}
                                        <td class="text-center">
                                            <a
                                                href="#!"
                                                on:click|preventDefault={openMR(
                                                    materia,
                                                    periodo
                                                )}
                                                >{valueOf(materia, periodo)}
                                            </a>
                                        </td>
                                    {/each}
                                    <td class="text-center text-dark">
                                        {Total(materia)}
                                    </td>
                                </tr>
                            {/each}
                        {/if}
                    </tbody>
                </Table>
            {:else if noexisten}
                <Alert color="warning">
                    <h4 class="alert-heading text-capitalize">
                        No existen inasistencias
                    </h4>
                    Para éste estudiante.
                </Alert>
            {/if}
        {/if}
    </ModalBody>
    <ModalFooter>
        <Button color="info" on:click={toggle}>
            <DoorClosed />
            Cerrar
        </Button>
    </ModalFooter>
</Modal>

<ModalReportePeriodo
    {openReporte}
    on:close={closeModalReporte}
    {inasistenciasMateria}
/>
