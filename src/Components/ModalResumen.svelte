<script>
    import { createEventDispatcher } from "svelte";
    import {
        Button,
        Modal,
        ModalBody,
        ModalFooter,
        ModalHeader,
        Table,
        Tooltip,
    } from "sveltestrap";
    import { DoorClosed } from "svelte-bootstrap-icons";
    import { urlPhp, iColegio } from "../Stores";
    export let open = false;
    export let estudianteResumen;
    let inasistencias;
    let totalInasistencias;
    let columnas;
    let filas;
    let datosResumen;
    const toggle = () => (open = !open);

    const dispatch = createEventDispatcher();
    const closeModal = () => {
        dispatch("close", {
            data: "close",
        });
    };

    const openingModal = async () => {
        let response = await fetch($urlPhp + "obtenerInasistencias.php", {
            method: "POST",
            body: JSON.stringify(estudianteResumen),
            headers: { "Content-Type": "application/json" },
            mode: "cors",
        });
        datosResumen = await response.json();
        inasistencias = datosResumen.resumen;
        totalInasistencias = datosResumen.totalInasistencias;
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
        });
        if (horas.length > 0) return horas[0].horas;
        else return "0";
    };

    const htmlInasistencia = (materia, periodo) => {
        console.log(totalInasistencias);
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
                    <td class='text-light'>
                        ${inas.fecha}
                    </td>
                    <td class='' style='color:yellow;'>
                        ${inas.horas} Horas
                    </td>
                    <td class='text-light'>
                        ${inas.excusa=="t"?"Excusa":"Sin excusa"}
                    </td>
            </tr>
           `;
        });
        html += "</table>";

        return html;
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
        <Table bordered hover>
            <thead>
                <th class="text-center">Materias</th>
                {#if columnas}
                    {#each columnas as periodo}
                        <th class="text-center text-success">{periodo}</th>
                    {/each}
                {/if}
            </thead>
            <tbody>
                {#if filas}
                    {#each filas as materia}
                        <tr>
                            <th scope="row">{materia}</th>
                            {#each columnas as periodo}
                                <td class="text-center"
                                    ><a href="#!" id={materia + periodo}
                                        >{valueOf(materia, periodo)}</a
                                    >
                                    <Tooltip
                                        target={materia + periodo}
                                        placement="right"
                                    >
                                        {@html htmlInasistencia(
                                            materia,
                                            periodo
                                        )}
                                    </Tooltip>
                                </td>
                            {/each}
                        </tr>
                    {/each}
                {/if}
            </tbody>
        </Table>
    </ModalBody>
    <ModalFooter>
        <Button color="info" on:click={toggle}>
            <DoorClosed />
            Cerrar
        </Button>
    </ModalFooter>
</Modal>
