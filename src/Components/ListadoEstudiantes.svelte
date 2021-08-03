<script>
  import {createEventDispatcher} from "svelte";
 export let estudiantes;
 export let cargandoEstudiantes;

 const dispatch = createEventDispatcher();

 const clickEstudiante = (estudiante)=>{
    dispatch('dataEstudiante',{
      data:estudiante
    })
 }
</script>

<div class="container d-flex justify-content-center pt-3">
  {#if estudiantes.length > 0 }
    <ul class="list-group pt-2">
      {#each estudiantes as estudiante}
        <li class="list-group-item"><a href="#!" on:click|preventDefault={()=>{
            clickEstudiante(estudiante)
        }}>{estudiante.nestudiante}</a></li>
      {/each}
    </ul>
  {:else if cargandoEstudiantes }
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  {/if}
</div>
