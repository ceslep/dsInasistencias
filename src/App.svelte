<script>
	import Inicio from './Components/Inicio.svelte';
	import { iColegio,docente } from './Stores.js';
	

const valores = window.location.search;
const urlParams = new URLSearchParams(valores);
$docente = urlParams.get('docente');
$iColegio=urlParams.get('icolegio');

</script>

<main>
	<Inicio/>
</main>

