<script>
    import { createEventDispatcher } from "svelte";
    import {
        Button,
        Modal,
        ModalBody,
        ModalFooter,
        ModalHeader,
        Table,
    } from "sveltestrap";
    import { DoorClosed } from "svelte-bootstrap-icons";
    import { urlPhp, iColegio } from "../Stores";
    export let open = false;
    export let estudianteResumen;
    let inasistencias;
    let columnas;
    let filas;
    let tabla = ["1"]["1"];
    const toggle = () => (open = !open);

    $: console.log(tabla);
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
        inasistencias = await response.json();
        console.log(inasistencias);
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
    $: if (columnas) console.log(columnas);
    $: if (filas) console.log(filas);
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
                <th>Materias</th>
                {#if columnas}
                    {#each columnas as periodo}
                        <th>{periodo}</th>
                    {/each}
                {/if}
            </thead>
        </Table>
    </ModalBody>
    <ModalFooter>
        <Button color="info" on:click={toggle}>
            <DoorClosed />
            Cerrar
        </Button>
    </ModalFooter>
</Modal>
