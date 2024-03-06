/*=========================================================================================
File Name: probability_chart.js
Description: For Probability chart
==========================================================================================*/

/***** PROBABILITY SETTINGS : START *****/
console.log(prizesData);
var prizesArray = prizesData;
var labels = [];
var data = [];
var backgroundColors  = [];

prizesData.forEach(element => {
    labels.push(element.title + ' ('+  element.probability + '%)');
    data.push(parseFloat(element.probability));
    var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
    backgroundColors.push(randomColor);

});

var data = {
    labels: labels,
    datasets: [{
        data: data,
        borderWidth: 1,
        backgroundColor: backgroundColors
    }]
};

var options = {
    responsive: true,
};

var ctx = document.getElementById('probabilityChart').getContext('2d');
var probabilityChart = new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: options
});

/***** PROBABILITY SETTINGS : END *****/
