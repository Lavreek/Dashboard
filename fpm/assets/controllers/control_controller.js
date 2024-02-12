import { Controller } from '@hotwired/stimulus';
import datepicker from 'js-datepicker'


export default class extends Controller {
    static targets = [ 'form' ]

    initialize() { }

    connect() { }

    disconnect() { }

    selectProgram(event) {
        const selector = document.getElementById('control_program')
        const form = this.formTarget

        selector.setAttribute('value', event.target.value)
        form.submit()
    }

    selectDate(event) {
        const selector = document.getElementById('control_program')
        const form = this.formTarget

        selector.removeAttribute('value')
        form.submit()
    }
}
