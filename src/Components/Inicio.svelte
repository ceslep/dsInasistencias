<script>
  import ListadoEstudiantes from "./ListadoEstudiantes.svelte";
  import { onMount } from "svelte";
  import { urlPhp, iColegio } from "../Stores";
  import ModalInasistencia from "./ModalInasistencia.svelte";
  
  let grados = [];
  let grupos = [];
  let estudiantes = [];
  let open;
  let estudiante;
  let cargandoEstudiantes = false;
  let datosFiltrarEstudiantes = {
    igrado: "",
    igrupo: "",
  };
  let validForm=false;
  let classBtn="btn btn-primary";

  onMount(async () => {
    grados = await obtenerGrados();
  });

  const obtenerGrados = async () => {
   
    grados = [];
    estudiantes = [];
    let response = await fetch(
      `${$urlPhp}obtenerGrados.php?icolegio=${$iColegio}`
    );
    return await response.json();
  };

  const obtenerGrupos = async () => {
    grupos = [];
    estudiantes = [];
    cargandoEstudiantes=false;
    if (datosFiltrarEstudiantes.igrado!=""){
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
  }
  };

  const obtenerEstudiantes = async () => {
    cargandoEstudiantes = true;
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
    cargandoEstudiantes = false;
  };

  const estudianteClick = (e) => {
   
    console.log(e.detail.data)
    estudiante=e.detail.data;
    open=true;
  };

  const closeModal = (e)=>{
    open= false;

  }

  $:validForm=datosFiltrarEstudiantes.igrado!="" && datosFiltrarEstudiantes.igrupo!="";
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
            class="{!validForm?"disabled "+classBtn:classBtn}"
            type="button"
            on:click={obtenerEstudiantes}>Ver</button
          >
        </div>
      </form>
    </div>
  </div>
</div>
{#if !cargandoEstudiantes}
<ListadoEstudiantes
  {estudiantes}
  {cargandoEstudiantes}
  on:dataEstudiante={estudianteClick}
/>
{/if}
<ModalInasistencia {open} {estudiante} consultando={!open} on:close={closeModal}/>
