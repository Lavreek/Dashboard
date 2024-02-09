import { Controller } from '@hotwired/stimulus';
import zoomPlugin from "chartjs-plugin-zoom";

export default class extends Controller {
    static targets = [ "canvas" ]

    connect() {
        this.element.addEventListener('chartjs:pre-connect', this._onPreConnect);
        this.element.addEventListener('chartjs:connect', this._onConnect);
        this.element.addEventListener('chartjs:init', this._onInitialize);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:pre-connect', this._onPreConnect);
        this.element.removeEventListener('chartjs:connect', this._onConnect);
        this.element.removeEventListener('chartjs:init', this._onInitialize);
    }

    _onInitialize(event) {
        const Chart = event.detail.Chart
        Chart.register(zoomPlugin)
    }

    _onPreConnect(event) {
        // The chart is not yet created
        // You can access the config that will be passed to "new Chart()"
        // console.log(event.detail.config);

        // For instance you can format Y axis

        event.detail.config.options.plugins = {
            legend : {
                position : "right",
                align : "start"
            },
            zoom: {
                zoom: {
                    wheel: {
                        enabled: true,
                    },
                    pinch: {
                        enabled: true
                    },
                    mode: 'xy',
                }
            }
        };

        event.detail.config.options.scales = {
            y: {
                ticks: {
                    callback: function (value, index, values) {

                    },
                },
            },
        };
    }

    _onConnect(event) {
        // The chart was just created
        // console.log(event.detail.chart); // You can access the chart instance using the event details

        // For instance you can listen to additional events

        document.dashboard = event.detail.chart

        event.detail.chart.options.onHover = (mouseEvent) => {
            /* ... */
        };

        event.detail.chart.options.onClick = (mouseEvent) => {
            event.detail.chart.resetZoom()
        };
    }

    defaultZoom() {
        document.dashboard.resetZoom()
    }
}
