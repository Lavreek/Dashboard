import { Controller } from '@hotwired/stimulus';
import datepicker from 'js-datepicker'


export default class extends Controller {
    initialize() {
        // const start = datepicker('#control_date-start', { id : 1 })
        // const end = datepicker('#control_date-end', { id : 1 })
    }

    connect() {
        this.element.addEventListener('submit', this._submitted)
    }

    disconnect() {
        this.element.removeEventListener('submit', this._submitted)
    }

    _submitted(event) {
        // event.preventDefault()
        console.log('here')
    }
}
