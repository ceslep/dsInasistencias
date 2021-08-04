<script>
  import { afterUpdate, createEventDispatcher, onMount } from "svelte";
  import {PersonLinesFill,BarChartLine} from "svelte-bootstrap-icons";
  import ModalResumen from "ModalResumen.svelte";
  export let estudiantes;
 
  let txtBusqueda="";
  let estudiantesLocal;
  const dispatch = createEventDispatcher();
  //export let cargandoEstudiantes;
  const clickEstudiante = (estudiante) => {
    dispatch("dataEstudiante", {
      data: estudiante,
    });
  };

  
  const buscarEstudiante= async (crit)=>{
    console.log(crit);
    console.log(estudiantes);
        let estudiantesFilter;
        if (crit.length>2){
        estudiantesFilter=estudiantes.filter(estudiante=>{
            return estudiante.nestudiante.toLowerCase().includes(crit);
        });
        estudiantesLocal=estudiantesFilter;
        }
        else estudiantesLocal=estudiantes;
        
    }

    onMount(()=>{
      console.log(estudiantes);
      estudiantesLocal=estudiantes;
    });
 
    const openModalResumen = ()=>{

    }

  $: buscarEstudiante(txtBusqueda);
</script>

<div class="container-fluid d-flex justify-content-center flex-column pt-3">
 
  {#if estudiantesLocal.length > 0}
  <input type="search" class="form-control" placeholder="Buscar Estudiante" bind:value={txtBusqueda}>
    <ul class="list-group pt-2">
      {#each estudiantesLocal as estudiante}
        <li class="list-group-item gradiente">
          <PersonLinesFill/>
          <a
            class="ms-2 align-middle"
            href="#!"
            on:click|preventDefault={() => {
              clickEstudiante(estudiante);
            }}>{estudiante.nestudiante}</a
          >
          <button class="btn btn-outline-primary btn-sm float-end" on:click={openModalResumen}>
            <BarChartLine style="color:orange;"/>
          </button>
        </li>
      {/each}
    </ul>
    
  {/if}
</div>

<ModalResumen/>

<style>
  .gradiente {
   
    background: linear-gradient(to right, #E2E2E2, #C9D6FF);
  }

  
</style>
