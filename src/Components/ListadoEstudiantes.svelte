<script>
  import { afterUpdate, createEventDispatcher, onMount } from "svelte";
  export let estudiantes;
  export let cargandoEstudiantes;
  let txtBusqueda="";
  let estudiantesLocal;
  const dispatch = createEventDispatcher();

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
 

  $: buscarEstudiante(txtBusqueda);
</script>

<div class="container-fluid d-flex justify-content-center flex-column pt-3">
  <input type="search" class="form-control" placeholder="Buscar Estudiante" bind:value={txtBusqueda}>
  {#if estudiantesLocal.length > 0}
  
    <ul class="list-group pt-2">
      {#each estudiantesLocal as estudiante}
        <li class="list-group-item gradiente">
          <a
            href="#!"
            on:click|preventDefault={() => {
              clickEstudiante(estudiante);
            }}>{estudiante.nestudiante}</a
          >
        </li>
      {/each}
    </ul>
  {:else if cargandoEstudiantes}
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  {/if}
</div>

<style>
  .gradiente {
   
    background: linear-gradient(to right, #E2E2E2, #C9D6FF);
  }

  
</style>
