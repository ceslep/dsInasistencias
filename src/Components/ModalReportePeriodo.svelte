<script>
    import  Swal  from "sweetalert2";
    import {
        createEventDispatcher,
        afterUpdate,
        onMount,
        beforeUpdate,
    } from "svelte";
    import { urlPhp, iColegio } from "../Stores";
    import {
        Button,
        Modal,
        ModalBody,
        ModalFooter,
        ModalHeader,
        Alert,
        Input,
        Spinner,
        Table,
    } from "sveltestrap";
    import { DoorClosed, Trash } from "svelte-bootstrap-icons";
    export let openReporte;
    export let inasistenciasMateria;
    let inasistencias;
    const toggle = () => (openReporte = !openReporte);
    const dispatch = createEventDispatcher();
    const closeModal = () => {
        dispatch("close", {
            data: "close",
        });
    };

    beforeUpdate(() => {
        if (inasistenciasMateria)
            inasistencias = inasistenciasMateria.map((inm) => {
                return {
                    id: inm.id,
                    Fecha: inm.fecha,
                    Horas: inm.horas,
                    Excusa: inm.excusa != "null" ? "Si" : "No",
                };
            });
    });
    const openingModal = async () => {};

    const borrarInasistencia = async (inasistencia) => {
        console.log(inasistencia);
        let result = await Swal.fire({
            title: "Está seguro?",
            text: "Si hace ésto no podrá revertirlo!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Borralo!",
            cancelButtonText:"Cancelar"
        });
        
            if (result.isConfirmed) {
                Swal.fire("Deleted!", "Your file has been deleted.", "success");
            }
            openReporte=false;
        
    };
</script>

<Modal
    isOpen={openReporte}
    {toggle}
    class="modal-dialog modal-dialog-centered"
    on:close={closeModal}
>
    <ModalHeader class="bg-secondary p-2 text-white bg-opacity-10" {toggle}
        >Inasistencias
    </ModalHeader>
    <ModalBody class="bg-dark">
        {#if inasistencias}
            <Table bordered hover>
                <thead>
                    <tr>
                        {#each Object.keys(inasistencias[0]) as itemInasistencia}
                            {#if itemInasistencia != "id"}
                                <th class="text-info text-center">
                                    {itemInasistencia}
                                </th>
                            {/if}
                        {/each}
                        <th class="text-danger text-center"> Borrar </th>
                    </tr>
                </thead>
                <tbody>
                    {#each inasistencias as inasistencia}
                        <tr>
                            {#each Object.values(inasistencia) as itemInasistencia, i}
                                {#if i > 0}
                                    <td
                                        class="text-light text-center align-middle"
                                    >
                                        {itemInasistencia}
                                    </td>
                                {/if}
                            {/each}
                            <td class="text-center align-middle">
                                <Button
                                    size="sm"
                                    color="danger"
                                    on:click={borrarInasistencia(inasistencia)}
                                >
                                    <Trash />
                                </Button>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </Table>
        {/if}
    </ModalBody>
    <ModalFooter class="bg-dark">
        <Button color="success" on:click={toggle}>
            <DoorClosed />
        </Button>
    </ModalFooter>
</Modal>
