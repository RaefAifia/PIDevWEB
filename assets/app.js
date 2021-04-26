/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.css';
import noUiSlider from 'nouislider'
import 'nouislider/distribute/nouislider.css'
//import 'nouislider/distribute/nouislider.min.css'
// start the Stimulus application

import './bootstrap';

console.log('heeey in assets/app.js');
const slider = document.getElementById('slider')
if(slider) {

        const min = document.getElementById('min')
        const max = document.getElementById('max')
        const minValue = Math.floor(parseInt(slider.dataset.min, 10) / 10) * 10
        const maxValue = Math.ceil(parseInt(slider.dataset.max, 10) / 10) * 10
        const range = noUiSlider.create(slider, {
            start: [min.value || minValue, max.value || maxValue],
            connect: true,
            step: 10,
            range: {
                'min': minValue,
                'max': maxValue
            }
        })
        range.on('slide', function (values, handle) {
            if (handle === 0) {
                min.value = Math.round(values[0])
            }
            if (handle === 1) {
                max.value = Math.round(values[1])
            }
        })
        range.on('end', function (values, handle) {
            if (handle===0) {
                min.dispatchEvent(new Event('change'))
            } else {
                max.dispatchEvent(new Event('change'))
            }
        })



}