<script>
  import { onMount } from "svelte";
  import { urlPhp, iColegio } from "../Stores";

  let grados = [];
  let grupos = [];
  let estudiantes = [];
  let cargandoEstudiantes=false;
  let datosFiltrarEstudiantes = {
    igrado: "",
    igrupo: "",
  };

  onMount(async () => {
    grados = await obtenerGrados();
  });

  const obtenerGrados = async () => {
    grados = [];
    estudiantes=[];
    let response = await fetch(
      `${$urlPhp}obtenerGrados.php?icolegio=${$iColegio}`
    );
    return await response.json();
  };

  const obtenerGrupos = async () => {
    grupos = [];
    estudiantes=[];
    let response = await fetch(`${$urlPhp}obtenerGrupos.php`, {
      method: "POST",
      body: JSON.stringify({
        icolegio: $iColegio,
        igrado: datosFiltrarEstudiantes.igrado,
      }),
      headers: { "Content-Type": "application/json" },
      mode: "cors",
    });
    grupos = await response.json();
  };

  const obtenerEstudiantes = async () => {
    cargandoEstudiantes=true;
    let response = await fetch(`${$urlPhp}obtenerEstudiantes.php`, {
      method: "POST",
      body: JSON.stringify({
        icolegio: $iColegio,
        igrado: datosFiltrarEstudiantes.igrado,
        igrupo: datosFiltrarEstudiantes.igrupo,
      }),
      headers: { "Content-Type": "application/json" },
      mode: "cors",
    });
    estudiantes = await response.json();
    cargandoEstudiantes=false;
  };
</script>

<div class="d-flex justify-content-center">
  <div class="card" style="width: 22rem;">
    <div class="card-header bg-secondary text-light">Ingresar</div>
    <div class="card-body">
      <form>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="grado">Grado</label>
              <select
                class="custom-select"
                bind:value={datosFiltrarEstudiantes.igrado}
                on:change={obtenerGrupos}
              >
                <option value="" />
                {#each grados as { igrado }}
                  <option value={igrado}>{igrado}</option>
                {/each}
              </select>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="grupo">Grupo</label>
              <select
                class="custom-select"
                bind:value={datosFiltrarEstudiantes.igrupo}
              >
                <option value="" />
                {#each grupos as { igrupo }}
                  <option value={igrupo}>{igrupo}</option>
                {/each}
              </select>
            </div>
          </div>
        </div>
        <div class="d-grid gap-1 pt-3">
          <button
            class="btn btn-primary"
            type="button"
            on:click={obtenerEstudiantes}>Ver</button
          >
        </div>
      </form>
    </div>
  </div>
  
</div>
<div class="container d-flex justify-content-center pt-3">
  {#if estudiantes.length > 0 }
    <ul class="list-group pt-2">
      {#each estudiantes as { nestudiante }}
        <li class="list-group-item"><a href="#!">{nestudiante}</a></li>
      {/each}
    </ul>
  {:else if cargandoEstudiantes }
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  {/if}
</div>

