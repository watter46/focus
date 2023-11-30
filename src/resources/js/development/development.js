import { Timer } from "./timer/timer";

window.addEventListener("livewire:init", () => {    
    let timer;

    window.setupTime = (defaultTime, remainingTime) => {
        timer = new Timer(defaultTime, remainingTime);
    }

    window.getRemainingTime = () => timer.getRemainingTime();
    
    Alpine.store('timer', {
        start() {
            timer.start();
        },
        stop() {
            timer.stop();
        }
    })
});