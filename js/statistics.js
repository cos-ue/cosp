var StatData = {};

/**
 * generates config for graph drawer
 * @param {int} ID of Element in StatData-Array
 * @param {char} type type of timedifferenz and sets unit for graph
 * @return {array} structured config
 */
function generateConfig(ID, type) {
    var unitSetter = "";
    switch (type) {
        case 'D':
            unitSetter = "day";
            break;
        case 'W':
            unitSetter = "day";
            break;
        case 'M':
            unitSetter = "month";
            break;
        case 'Y':
            unitSetter = 'month';
            break;
    }
    return {
        type: 'line',
        data: {
            datasets: StatData[ID].data
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: unitSetter
                    },
                    ticks: {
                        fontColor: '#ffffff',
                        autoSkip: true,
                        maxTicksLimit: StatData[ID].Amount
                    },
                    distribution: 'series',
                    gridLines: {
                        color: 'rgba(255,255,255,1)'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#ffffff'
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,1)'
                    }
                }],
            },
            legend:{
                labels: {
                    fontColor: '#ffffff'
                },
                position: 'bottom'
            }
        }
    }
}

/**
 * loads all needed Information from API
 */
function loadStatisticalData(){
    var result = sendApiRequest({type: 'gsd', data: loading}, false).data;
    for (var i = 0; i < result.length; i++ ){
        if (result[i].type == "Y"){
            result[i].Amount = 12;
        }
        StatData[result[i].ID] = result[i];
    }
}