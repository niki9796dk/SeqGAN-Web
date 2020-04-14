<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 26-03-2020 - 15:15
 */

$_sql = new Sql();
$experiment_id = $_REQUEST["id"];

?>

<div class="bg-white" style="width: 600px; height: fit-content">
    <canvas id="canvas"></canvas>
</div>
<br>
<br>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="/js/chart_utils.js"></script>
<script>
    var data = <?=json_encode($_sql->SELECT_lossDataForExperiment($experiment_id))?>;
    var config = {
        type: 'line',
        data: {
            labels: getDataColumn(data, "epoch_nr"),
            datasets: [{
                label: 'Mean Discriminator Loss',
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: getDataColumn(data, "mean_disc_loss"),
                fill: false,
            }, {
                label: 'Mean Generator Loss',
                fill: false,
                backgroundColor: window.chartColors.orange,
                borderColor: window.chartColors.orange,
                data: getDataColumn(data, "mean_gen_loss"),
            }, {
                label: "optimal limit",
                fill: false,
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: new Array(data.length).fill(0.69*2),
                borderDash: [10,5],
            }]
        },
        options: {
            aspectRatio: 1,
            responsive: true,
            title: {
                display: true,
                text: 'Mean loss'
            },
            elements: {
                point: {
                    radius: 0,
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Epoch'
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Loss'
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },
            legend: {
                labels: {
                    filter: function(item, chart) {
                        // Logic to remove a particular legend item goes here
                        return !item.text.includes('optimal limit');
                    }
                }
            },

            tooltips: {
                mode: 'index',
                intersect: false,
                enabled: false, // Disable the on-canvas tooltip

                custom: function(tooltipModel) {
                    // Tooltip Element
                    var tooltipEl = document.getElementById('chartjs-tooltip');

                    // Create element on first render
                    if (!tooltipEl) {
                        tooltipEl = document.createElement('div');
                        tooltipEl.id = 'chartjs-tooltip';
                        tooltipEl.innerHTML = '<table></table>';
                        document.body.appendChild(tooltipEl);
                    }

                    // Hide if no tooltip
                    if (tooltipModel.opacity === 0) {
                        tooltipEl.style.opacity = 0;
                        return;
                    }

                    // Set caret Position
                    tooltipEl.classList.remove('above', 'below', 'no-transform');
                    if (tooltipModel.yAlign) {
                        tooltipEl.classList.add(tooltipModel.yAlign);
                    } else {
                        tooltipEl.classList.add('no-transform');
                    }

                    function getBody(bodyItem) {
                        return bodyItem.lines;
                    }

                    // Set Text
                    if (tooltipModel.body) {
                        var titleLines = tooltipModel.title || [];
                        var bodyLines = tooltipModel.body.map(getBody);

                        var innerHtml = '<thead>';

                        titleLines.forEach(function(title) {
                            innerHtml += '<tr><th>Epoch: <span id="epoch_loss">' + title + '</span></th></tr>';
                        });
                        innerHtml += '</thead><tbody>';

                        bodyLines.forEach(function(body, i) {
                            if (body[0].includes("optimal limit")) {
                                return false;
                            }

                            var colors = tooltipModel.labelColors[i];
                            var style = 'background:' + colors.backgroundColor;
                            style += '; border-color:' + colors.borderColor;
                            style += '; border-width: 2px';
                            var span = '<span style="' + style + '"></span>';
                            innerHtml += '<tr><td>' + span + body + '</td></tr>';
                        });
                        innerHtml += "<tr><td id='image_col_loss'></td></tr>";
                        innerHtml += '</tbody>';

                        var tableRoot = tooltipEl.querySelector('table');
                        tableRoot.innerHTML = innerHtml;

                        setTimeout(function () {
                            if ($("#epoch_loss").text() == titleLines[0]) {
                                let image = "<img style='width: 250px;' src='/image/getImage.php?experiment_id=<?=$experiment_id?>&epoch="+titleLines[0]+"&small=false'>"

                                $("#image_col_loss").html(image);

                                console.log(image);
                            }

                        }, 200)
                    }

                    // `this` will be the overall tooltip
                    var position = this._chart.canvas.getBoundingClientRect();

                    // Display, position, and set styles for font
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.position = 'absolute';
                    tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                    tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                    tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                    tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                    tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                    tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
                    tooltipEl.style.pointerEvents = 'none';
                }
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = new Chart(ctx, config);
    };

    function getDataColumn(data, column) {
        return data.map(function(value, index){ return value[column]})
    }
</script>
