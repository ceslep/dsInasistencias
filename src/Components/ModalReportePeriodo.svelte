<script>
    import { createEventDispatcher, afterUpdate, onMount } from "svelte";
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
     } from "sveltestrap";
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
                    <th class="text-center text-primary">
                        TOTAL
                    </th>
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
                                        title="<em>Tooltip</em> <u>with</u> <b>HTML</b>"
                                    >
                                        {@html htmlInasistencia(
                                            materia,
                                            periodo
                                        )}
                                    </Tooltip>
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
    </ModalBody>
    <ModalFooter>
        <Button color="info" on:click={toggle}>
            <DoorClosed />
            Cerrar
        </Button>
    </ModalFooter>
</Modal>
