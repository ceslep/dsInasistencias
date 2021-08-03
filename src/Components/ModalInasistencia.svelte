
<script lang="ts">
	import { createEventDispatcher, afterUpdate, onMount} from 'svelte';
  import { urlPhp, iColegio } from "../Stores";
  import {
    Button,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
    Alert,
    Input
  } from 'sveltestrap';
  import  moment from "moment";
  export let open = false;
  export let estudiante;
  let materias:any=[];
  let fechahora;
  export let consultando:boolean=false;
  const toggle = () => (open = !open);

  const dispatch = createEventDispatcher();
  const closeModal = ()=>{
    dispatch('close',{
      data:'close'
    });
  }

  let inasistencia={
    codigo:"",
    imateria:"",
    horas:"",
    fecha:"",
    excusa:false
  }


  const obtenerMaterias = async () => {
    
    if (estudiante&&!consultando){
      consultando=true;
    let response = await fetch(`${$urlPhp}obtenerMaterias.php`, {
      method: "POST",
      body: JSON.stringify({
        icolegio: $iColegio,
        codigo:estudiante.codigo
      }),
      headers: { "Content-Type": "application/json" },
      mode: "cors",
    });
    materias = await response.json();
  }
  };

  onMount(()=>{
   moment.locale('es-CO');
  });
  afterUpdate(()=>{
    inasistencia.fecha=moment().format("YYYY-MM-DD");
     obtenerMaterias();
  });

  $:if(estudiante) inasistencia.codigo=estudiante.codigo;

  $:console.log(inasistencia);
</script>

<div>
  
  <Modal isOpen={open}
  on:close={closeModal}
  >
    <ModalHeader >{estudiante.nestudiante}<br/>Cod:{estudiante.codigo}</ModalHeader>
    <ModalBody>
      <Alert color="primary">
        <div class="form-control">
          <label for="materia">Materia</label>
          <select class="custom-select w-100" bind:value={inasistencia.imateria}>
            <option value=""></option>
            {#each materias as {imateria}}
               <option value="{imateria}">{imateria}</option>
            {/each}
          </select>
        </div>
        <div class="form-control">
          <label for="materia">Horas de inasistencia</label>
          <select class="custom-select w-100" bind:value={inasistencia.horas}>
            <option value=""></option>
            {#each [1,2,3,4,5] as hora}
               <option value="{hora}">{hora}</option>
            {/each}
          </select>
        </div>
        <div class="form-control">
          <label for="fecha">Fecha</label>
          <Input
          type="date"
          name="datetime"
          id="exampleDatetime"
          placeholder="datetime placeholder"
          bind:value={inasistencia.fecha}
        />
        </div>
        <div class="form-check form-switch pt-2">
          <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" bind:checked={inasistencia.excusa}>
          <label class="form-check-label" for="flexSwitchCheckDefault">Con excusa</label>
        </div>
      </Alert>
    </ModalBody>
    <ModalFooter>
      <Button color="primary" on:click={toggle}>Guardar</Button>
      <Button color="secondary" on:click={toggle}>Cerrar</Button>
    </ModalFooter>
  </Modal>
</div>