//Courbe_Dashboard
$(function () {
	var number_client = document.getElementById("number_client").textContent;
	var number_vehicule = document.getElementById("number_vehicule").textContent;
	var number_contrat = document.getElementById("number_contrat").textContent;

	"use strict";
	//chartcourbe
	var options = {
		series: [{
			name: 'Nombre total',
			data: [number_client, number_vehicule, number_contrat]
		}],
		chart: {
			foreColor: '#000000',
			type: 'bar',
			height: 300,
			toolbar: {
				show: false
			},
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: false,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.10,
			},
			sparkline: {
				enabled: false
			}
		},
		grid: {
		   show: true,
		   borderColor: '#ededed',
		   strokeDashArray: 4,
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: '20%',
				endingShape: 'rounded'
			},
		},
		markers: {
			size: 4,
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			}
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			show: false,
			width: 0,
			curve: 'smooth'
		},
		colors: ["#EB0007"],
		xaxis: {
			categories: ['Clients', 'Véhicules', 'Contrats'],
		},
		fill: {
			opacity: 1
		}
	};
	var chart = new ApexCharts(document.querySelector("#chartcourbe"), options);
	chart.render();
});

// Chart Donut Dashboard SUPER
$(function () {
// chart donut
var vehicule_vendue = parseInt(document.getElementById('vehicule_vendue').value);
var vehicule_entretien = parseInt(document.getElementById('vehicule_entretien').value);
var vehicule_disponible = parseInt(document.getElementById('vehicule_disponible').value);

var options = {
	series: [vehicule_disponible,vehicule_vendue,vehicule_entretien],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
						show: true,
						fontSize: '50px',
							fontWeight: 1000,
						color: '#000000',
						formatter: function (val) {
						  	return val + "%"
						},
						offsetY: 20,
					},
					total: {
					  	show: true,
					  	formatter: function (val) {
						  	return vehicule_disponible + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#47DC1C", "#23790B" , "#D9D9D9"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Véhicules Disponible', 'Véhicules Vendue', 'Véhicules En Entretien'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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

var materiel_disponible = parseInt(document.getElementById('materiel_disponible').value);
var materiel_entretien = parseInt(document.getElementById('materiel_entretien').value);
var materiel_hors_service = parseInt(document.getElementById('materiel_hors_service').value);
var options = {
	series: [materiel_disponible,materiel_entretien,materiel_hors_service],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
					  	show: true,
					  	fontSize: '50px',
              			fontWeight: 1000,
					  	color: '#000000',
					  	formatter: function (val) {
							return val + "%"
					  	},
					  	offsetY: 20,
					},
					total: {
						show: true,
						formatter: function (val) {
							return materiel_disponible + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#2000C8", "#5C92FF", "#D9D9D9"],
	dataLabels: {
		enabled: false,
      
	},
	labels: ['Materiels Disponibles', 'Materiels En Entretien', 'Materiels Hors Service'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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
var chart = new ApexCharts(document.querySelector("#charttauxmateriel"), options);
chart.render();

var pack_activé = parseInt(document.getElementById('pack_activé').value);
var pack_désactivé = parseInt(document.getElementById('pack_désactivé').value);
var options = {
	series: [pack_activé,pack_désactivé],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
					  	show: true,
					  	fontSize: '50px',
              			fontWeight: 1000,
					  	color: '#000000',
					  	formatter: function (val) {
							return val + "%"
					  	},
						offsetY: 20,
					},
					total: {
						show: true,
						formatter: function (val) {
							return pack_activé + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#C101B5", "#D9D9D9"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Packs Activé', 'Packs Désactivé'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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
var chart = new ApexCharts(document.querySelector("#charttauxpacks"), options);
chart.render();

var entretien_véhicule = parseInt(document.getElementById('entretien_véhicule').value);
var entretien_materiel = parseInt(document.getElementById('entretien_materiel').value);
var options = {
	series: [entretien_véhicule,entretien_materiel],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
						show: true,
						fontSize: '50px',
							fontWeight: 1000,
						color: '#000000',
						formatter: function (val) {
						  	return val + "%"
						},
						offsetY: 20,
					},
					total: {
					  	show: true,
					  	formatter: function (val) {
						  	return entretien_véhicule + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#D71218", "#D9D9D9"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Entretien Véhicules', 'Entretien Matériels'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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
var chart = new ApexCharts(document.querySelector("#charttauxentretien"), options);
chart.render();

var contrat_véhicule = parseInt(document.getElementById('contrat_véhicule').value);
var contrat_materiel = parseInt(document.getElementById('contrat_materiel').value);
var contrat_pack = parseInt(document.getElementById('contrat_pack').value);

var options = {
	series: [contrat_véhicule,contrat_materiel,contrat_pack],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
						show: true,
						fontSize: '50px',
							fontWeight: 1000,
						color: '#000000',
						formatter: function (val) {
						  	return val + "%"
						},
						offsetY: 20,
					},
					total: {
					  	show: true,
					  	formatter: function (val) {
						  	return contrat_véhicule + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#FF7403","#F5A212","#D9D9D9"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Contrat Véhicule','Contrat Matériel','Contrat Pack'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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
var chart = new ApexCharts(document.querySelector("#charttauxcontrat"), options);
chart.render();

var facture_véhicule = parseInt(document.getElementById('facture_véhicule').value);
var facture_materiel = parseInt(document.getElementById('facture_materiel').value);
var facture_pack = parseInt(document.getElementById('facture_pack').value);
if(isNaN(facture_véhicule) && isNaN(facture_materiel) && isNaN(facture_pack)){
	facture_véhicule = 0;
	facture_materiel = 0;
	facture_pack = 0;
}
var options = {
	series: [facture_véhicule,facture_materiel,facture_pack],
	chart: {
		foreColor: '#373d3f',
		height: 380,
		type: 'donut',
	},
	legend: {
		position: 'bottom',
		show: true,
	},
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: 80,
				labels: {
					show: true,
					name: {
					  show: false,
					},
					value: {
						show: true,
						fontSize: '50px',
							fontWeight: 1000,
						color: '#000000',
						formatter: function (val) {
						  	return val + "%"
						},
						offsetY: 20,
					},
					total: {
					  	show: true,
					  	formatter: function (val) {
						  	return facture_véhicule + "%"
						},
					}
				}
			}
		}
	},
	colors: ["#050D74","#416798","#D9D9D9"],
	dataLabels: {
		enabled: false,
	},
	labels: ['Facture Véhicule','Facture Matériel','Facture Pack'],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
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
var chart = new ApexCharts(document.querySelector("#charttauxfactures"), options);
chart.render();
});

// Chart Donut Dashboard Agence
$(function () {
	// chart donut
	var vehicule_vendue_agent = parseInt(document.getElementById('vehicule_vendue_agent').value);
	var vehicule_entretien_agent = parseInt(document.getElementById('vehicule_entretien_agent').value);
	var vehicule_disponible_agent = parseInt(document.getElementById('vehicule_disponible_agent').value);
	
	var options = {
		series: [vehicule_disponible_agent,vehicule_vendue_agent,vehicule_entretien_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							show: true,
							fontSize: '50px',
								fontWeight: 1000,
							color: '#000000',
							formatter: function (val) {
								  return val + "%"
							},
							offsetY: 20,
						},
						total: {
							  show: true,
							  formatter: function (val) {
								  return vehicule_disponible_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#47DC1C", "#23790B" , "#D9D9D9"],
		dataLabels: {
			enabled: false,
		},
		labels: ['Véhicules Disponible', 'Véhicules Vendue', 'Véhicules En Entretien'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxvehicule_agent"), options);
	chart.render();
	
	var materiel_disponible_agent = parseInt(document.getElementById('materiel_disponible_agent').value);
	var materiel_entretien_agent = parseInt(document.getElementById('materiel_entretien_agent').value);
	var materiel_hors_service_agent = parseInt(document.getElementById('materiel_hors_service_agent').value);
	var options = {
		series: [materiel_disponible_agent,materiel_entretien_agent,materiel_hors_service_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							  show: true,
							  fontSize: '50px',
							  fontWeight: 1000,
							  color: '#000000',
							  formatter: function (val) {
								return val + "%"
							  },
							  offsetY: 20,
						},
						total: {
							show: true,
							formatter: function (val) {
								return materiel_disponible_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#2000C8", "#5C92FF", "#D9D9D9"],
		dataLabels: {
			enabled: false,
		  
		},
		labels: ['Materiels Disponibles', 'Materiels En Entretien', 'Materiels Hors Service'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxmateriel_agent"), options);
	chart.render();
	
	var pack_activé_agent = parseInt(document.getElementById('pack_activé_agent').value);
	var pack_désactivé_agent = parseInt(document.getElementById('pack_désactivé_agent').value);
	var options = {
		series: [pack_activé_agent,pack_désactivé_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							  show: true,
							  fontSize: '50px',
							  fontWeight: 1000,
							  color: '#000000',
							  formatter: function (val) {
								return val + "%"
							  },
							offsetY: 20,
						},
						total: {
							show: true,
							formatter: function (val) {
								return pack_activé_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#C101B5", "#D9D9D9"],
		dataLabels: {
			enabled: false,
		},
		labels: ['Packs Activé', 'Packs Désactivé'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxpacks_agent"), options);
	chart.render();
	
	var entretien_véhicule_agent = parseInt(document.getElementById('entretien_véhicule_agent').value);
	var entretien_materiel_agent = parseInt(document.getElementById('entretien_materiel_agent').value);
	var options = {
		series: [entretien_véhicule_agent,entretien_materiel_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							show: true,
							fontSize: '50px',
								fontWeight: 1000,
							color: '#000000',
							formatter: function (val) {
								  return val + "%"
							},
							offsetY: 20,
						},
						total: {
							  show: true,
							  formatter: function (val) {
								  return entretien_véhicule_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#D71218", "#D9D9D9"],
		dataLabels: {
			enabled: false,
		},
		labels: ['Entretien Véhicules', 'Entretien Matériels'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxentretien_agent"), options);
	chart.render();
	
	var contrat_véhicule_agent = parseInt(document.getElementById('contrat_véhicule_agent').value);
	var contrat_materiel_agent = parseInt(document.getElementById('contrat_materiel_agent').value);
	var contrat_pack_agent = parseInt(document.getElementById('contrat_pack_agent').value);
	
	var options = {
		series: [contrat_véhicule_agent,contrat_materiel_agent,contrat_pack_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							show: true,
							fontSize: '50px',
								fontWeight: 1000,
							color: '#000000',
							formatter: function (val) {
								  return val + "%"
							},
							offsetY: 20,
						},
						total: {
							  show: true,
							  formatter: function (val) {
								  return contrat_véhicule_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#FF7403","#F5A212","#D9D9D9"],
		dataLabels: {
			enabled: false,
		},
		labels: ['Contrat Véhicule','Contrat Matériel','Contrat Pack'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxcontrat_agent"), options);
	chart.render();
	
	var facture_véhicule_agent = parseInt(document.getElementById('facture_véhicule_agent').value);
	var facture_materiel_agent = parseInt(document.getElementById('facture_materiel_agent').value);
	var facture_pack_agent = parseInt(document.getElementById('facture_pack_agent').value);
	if(isNaN(facture_véhicule_agent) && isNaN(facture_materiel_agent) && isNaN(facture_pack_agent)){
		facture_véhicule_agent = 0;
		facture_materiel_agent = 0;
		facture_pack_agent = 0;
	}
	var options = {
		series: [facture_véhicule_agent,facture_materiel_agent,facture_pack_agent],
		chart: {
			foreColor: '#373d3f',
			height: 380,
			type: 'donut',
		},
		legend: {
			position: 'bottom',
			show: true,
		},
		plotOptions: {
			pie: {
				customScale: 0.8,
				donut: {
					size: 80,
					labels: {
						show: true,
						name: {
						  show: false,
						},
						value: {
							show: true,
							fontSize: '50px',
								fontWeight: 1000,
							color: '#000000',
							formatter: function (val) {
								  return val + "%"
							},
							offsetY: 20,
						},
						total: {
							  show: true,
							  formatter: function (val) {
								  return facture_véhicule_agent + "%"
							},
						}
					}
				}
			}
		},
		colors: ["#050D74","#416798","#D9D9D9"],
		dataLabels: {
			enabled: false,
		},
		labels: ['Facture Véhicule','Facture Matériel','Facture Pack'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					width: 200
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
	var chart = new ApexCharts(document.querySelector("#charttauxfactures_agent"), options);
	chart.render();
	});