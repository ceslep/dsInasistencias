<script>
    import { createEventDispatcher } from "svelte";
    import {
        Button,
        Modal,
        ModalBody,
        ModalFooter,
        ModalHeader,
    } from "sveltestrap";
    import { DoorClosed } from "svelte-bootstrap-icons";
    export let open = false;
    export let estudianteResumen;
    const toggle = () => (open = !open);

    const dispatch = createEventDispatcher();
    const closeModal = () => {
        dispatch("close", {
            data: "close",
        });
    };
</script>

<Modal
    isOpen={open}
    {toggle}
    class="modal-dialog modal-dialog-centered"
    on:close={closeModal}
>
    <ModalHeader class="bg-success p-2 text-white bg-opacity-10" {toggle}
        >Resumen Inasistencias
    </ModalHeader>
    <ModalBody>
       {estudianteResumen.nestudiante}
    </ModalBody>
    <ModalFooter>
        <Button color="info" on:click={toggle}>
            <DoorClosed />
            Cerrar
        </Button>
    </ModalFooter>
</Modal>
