<script>
    import { urlPhp, iColegio } from "./../Stores.js";
    import Chart from "chart.js/auto";
    import { afterUpdate } from "svelte";

    export let datosFiltrarEstudiantes;

    let canvas;
    let dataC;
    let labelsC;
    let bckColor;
    let brdColor;

    afterUpdate(async () => {
        let response = await fetch($urlPhp + "repInasistencias.php", {
            method: "POST",
            body: JSON.stringify({
                ...datosFiltrarEstudiantes,
                icolegio: $iColegio,
            }),
            headers: { "Content-Type": "application/json" },
            mode: "cors",
        });
        let datos = await response.json();
        dataC = datos.map((d) => {
            return parseInt(d.horas);
        });
        labelsC = datos.map((d) => {
            return d.imateria;
        });
        bckColor = datos.map((d) => {
            return `rgba( ${parseInt(100 + 155 * Math.random())}, ${parseInt(
                100 + 155 * Math.random()
            )}, ${parseInt(100 + 155 * Math.random())}, 0.2)`;
        });
        brdColor = datos.map((d) => {
            return `rgba( ${parseInt(100 + 155 * Math.random())}, ${parseInt(
                100 + 155 * Math.random()
            )}, ${parseInt(100 + 155 * Math.random())}, 1)`;
        });
        drawChart();
    });

    const drawChart = () => {
        new Chart(canvas, {
            type: "bar",
            data: {
                labels: labelsC,
                datasets: [
                    {
                        label: "Inasistencias",
                        data: dataC,
                        backgroundColor: bckColor,
                        borderColor: brdColor,
                        borderWidth: 1,
                        stack: 's 0'
                    },
                    {
                        label: "Inasistencias",
                        data: dataC,
                        backgroundColor: bckColor,
                        borderColor: brdColor,
                        borderWidth: 1,
                        stack: 's 1',
                        type:"line"
                    }
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
            responsive:true
        });
    };

   
</script>

<div class="chart-container">
    <canvas bind:this={canvas} id="myChart" width="400" height="400" />
</div>

<style>
    .chart-container {
        position: relative;
        margin: auto;
        height: 80vh;
        width: 80vw;
    }
</style>
