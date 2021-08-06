<script>
  import ListadoEstudiantes from "./ListadoEstudiantes.svelte";
  import { onMount } from "svelte";
  import { urlPhp, iColegio,docente } from "../Stores";
  import ModalInasistencia from "./ModalInasistencia.svelte";
  import {ClipboardData,Eye} from "svelte-bootstrap-icons";
  import {Spinner} from "sveltestrap";
  let grados = [];
  let grupos = [];
  let estudiantes = [];
  let open;
  let estudiante;
  let cargandoEstudiantes = false;
  let cargandoGrados=false;
  let cargando=false;
  let cargandoGrupos=false;
  let datosFiltrarEstudiantes = {
    igrado: "",
    igrupo: "",
  };
  let validForm = false;
  let classBtn = "btn btn-primary w-100";

  onMount(async () => {
    grados = await obtenerGrados();
  });

  const obtenerGrados = async () => {
    grados = [];
    estudiantes = [];
    cargandoGrados=true;
    let data;
    let response = await fetch(
      `${$urlPhp}obtenerGrados.php?icolegio=${$iColegio}`
    );
    data = await response.json();
    cargandoGrados=false;
    return data;
  };

  const obtenerGrupos = async () => {
    grupos = [];
    estudiantes = [];
    cargandoEstudiantes = false;
    cargandoGrupos=true;
    if (datosFiltrarEstudiantes.igrado != "") {
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
      cargandoGrupos=false;
    }
  };

  const obtenerEstudiantes = async () => {
    cargandoEstudiantes = true;
    cargando=true;
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
    cargando=false;
  };

  const estudianteClick = (e) => {
    console.log(e.detail.data);
    estudiante = e.detail.data;
    open = true;
  };

  const closeModal = (e) => {
    open = false;
  };

  $: validForm =
    datosFiltrarEstudiantes.igrado != "" &&
    datosFiltrarEstudiantes.igrupo != "";
</script>

<div class="d-flex justify-content-center">
  <div class="card" style="width: 22rem;">
    <div class="card-header bg-secondary text-light">Ingresar {$docente} {$iColegio}</div>
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
                  <option data-tokens={igrado} value={igrado}>{igrado}</option>
                {/each}
              </select>
              {#if cargandoGrados}
              <Spinner size="sm"/>
              {/if}
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
              {#if cargandoGrupos}
              <Spinner size="sm"/>
              {/if}
            </div>
          </div>
        </div>
        <div class="d-grid gap-1 pt-3">
          <div class="row">
            <div class="col">
              <button
                class={!validForm ? "disabled " + classBtn : classBtn}
                type="button"
                on:click={obtenerEstudiantes}><Eye style="color:aqua;"/><span class="ms-2">Ver</span>
                {#if cargando}
                <Spinner size="sm"/>
                {/if}
                </button
              >
            </div>
            <div class="col">
              <button
                class="btn btn-secondary  w-100"
                type="button"
                on:click={obtenerEstudiantes}><ClipboardData style="color:yellow;"/><span class="ms-2">Resumen</span></button
              >
            </div>
          </div>
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
<ModalInasistencia
  {open}
  {estudiante}
  consultando={!open}
  on:close={closeModal}
/>

