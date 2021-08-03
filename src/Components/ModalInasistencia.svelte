
<script lang="ts">
	import { createEventDispatcher } from 'svelte';
  import {
    Button,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
  } from 'sveltestrap';
  export let open = false;
  export let estudiante;
  const toggle = () => (open = !open);
</script>

<div>
  
  <Modal isOpen={open} >
    <ModalHeader >Modal title</ModalHeader>
    <ModalBody>
     {JSON.stringify(estudiante)}
    </ModalBody>
    <ModalFooter>
      <Button color="primary" on:click={toggle}>Do Something</Button>
      <Button color="secondary" on:click={toggle}>Cancel</Button>
    </ModalFooter>
  </Modal>
</div>