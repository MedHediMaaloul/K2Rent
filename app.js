// Donut 
var vehicule_loue = parseInt(document.getElementById('vehicule_loue').value);
var vehicule_dispo = parseInt(document.getElementById('vehicule_dispo').value);

var options = {
	series: [vehicule_loue,vehicule_dispo],
	chart: {
		foreColor: '#373d3f',
		height: '100%',
		width: '100%',
		type: 'donut',
	},
	legend: {
		position: 'right',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 70,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
						show: true,
						fontSize: '30px',
						fontWeight: 50,
						color: '#000000',
						formatter: function (val) {
						  	return val + "%"
						},
						offsetY: 5,
					},
					total: {
					  	show: true,
					  	formatter: function (val) {
						  	return vehicule_loue + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#000000", "#E3E3E3"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Voitures louées', 'Voitures libres'],
	responsive: [{
		breakpoint: 500,
		options: {
			chart: {
				width: 800
			},
			legend: {
				position: 'bottom'
			},
			plotOptions: {
				pie: {
					customScale: 1,
				}
			},
		}
	}]
};
var chart = new ApexCharts(document.querySelector("#charttauxvehicule"), options);
chart.render();


// courbe
$(function () {
	var janvier = document.getElementById("janvier").value;
	var février = document.getElementById("février").value;
	var mars = document.getElementById("mars").value;
	var avril = document.getElementById("avril").value;
	var mai = document.getElementById("mai").value;
	var juin = document.getElementById("juin").value;
	var juillet = document.getElementById("juillet").value;
	var aout = document.getElementById("aout").value;
	var septembre = document.getElementById("septembre").value;
	var octobre = document.getElementById("octobre").value;
	var novembre = document.getElementById("novembre").value;
	var decembre = document.getElementById("decembre").value;
	var options = {
		series: [{
			name: 'Nombre total',
			data: [janvier, février, mars, avril, mai, juin, juillet, aout, septembre, octobre, novembre, decembre]
		}],
		chart: {
			foreColor: '#000000',
			type: 'bar',
			height: '100%',
            width: '100%',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            parentHeightOffset: 15,
		},
		grid: {
		   show: true,
		   borderColor: '#D9D9D9',
		   strokeDashArray: 0,
		},
		plotOptions: {
			bar: {
				columnWidth: '20%',
			},
		},
		dataLabels: {
			enabled: false
		},
		colors: ["#000000"],
		xaxis: {
			categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
            tickAmount: 6,
            title: {
                text: '(Mois)',
                offsetX: 10,
                offsetY: 0,
                style: {
                    color: '#828282',
                    fontSize: '10px',
                    fontWeight: 500,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
		},
        yaxis: {
            axisBorder: {
                show: true,
                color: '#78909C',
                offsetX: 0,
                offsetY: 0
            },
            title: {
                text: '(Nombre de location)',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: '#828282',
                    fontSize: '10px',
                    fontWeight: 500,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
            crosshairs: {
                show: true,
                position: 'back',
                stroke: {
                    color: '#b6b6b6',
                    width: 1,
                    dashArray: 0,
                },
            },
            tooltip: {
                enabled: true,
                offsetX: 0,
            },
        },
	};
	var chart = new ApexCharts(document.querySelector("#chartcourbe"), options);
	chart.render();
});

$(function () {
	var chiffrejanvier = document.getElementById("chiffrejanvier").value;
	var chiffrefévrier = document.getElementById("chiffrefévrier").value;
	var chiffremars = document.getElementById("chiffremars").value;
	var chiffreavril = document.getElementById("chiffreavril").value;
	var chiffremai = document.getElementById("chiffremai").value;
	var chiffrejuin = document.getElementById("chiffrejuin").value;
	var chiffrejuillet = document.getElementById("chiffrejuillet").value;
	var chiffreaout = document.getElementById("chiffreaout").value;
	var chiffreseptembre = document.getElementById("chiffreseptembre").value;
	var chiffreoctobre = document.getElementById("chiffreoctobre").value;
	var chiffrenovembre = document.getElementById("chiffrenovembre").value;
	var chiffredecembre = document.getElementById("chiffredecembre").value;
	var options = {
		series: [{
		  name: "Chiffre",
		  data: [chiffrejanvier, chiffrefévrier, chiffremars, chiffreavril, chiffremai, chiffrejuin, chiffrejuillet, chiffreaout, chiffreseptembre, chiffreoctobre, chiffrenovembre, chiffredecembre],
		  min: 10,
		  max: 900
		}],
		chart: {
			height: '100%',
			width: '100%',
			type: 'area',
			zoom: {
		  	enabled: false
			},
  		},
		colors: ['#000000'],
  		dataLabels: {
			enabled: false
  		},
  		stroke: {
			width: [4, 14, 20],
			curve: 'smooth',
			dashArray: [0, 8, 5]
  		},
  		xaxis: {
			categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
			title: {
				text: '(Mois)',
				offsetX: 0,
				offsetY: 0,
				style: {
					color: '#828282',
					fontSize: '10px',
					fontWeight: 500,
					cssClass: 'apexcharts-xaxis-title',
				},
			},
  		},
		yaxis: {
			title: {
				text: '(Chiffre en DT)',
				style: {
					color: '#828282',
					fontSize: '10px',
					fontWeight: 500,
					cssClass: 'apexcharts-xaxis-title',
				},
			},
		},
  		tooltip: {
		y: [{title: {
			formatter: function (val) {
						return val + " (DT)"
			  		}
			}
		},]},
  		grid: {
			borderColor: '#D9D9D9',
  		}
	};
  	var chart = new ApexCharts(document.querySelector("#chiffreaffaire"), options);
  	chart.render();
});