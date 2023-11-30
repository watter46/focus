import { Cursor } from "./cursor";

export class Timer {
    #remainingTime_sec;
    #defaultTime_sec;
    #cursor;

    #timerId;
    #endTime;

    constructor(defaultTime_sec, remainingTime) { 
        this.#defaultTime_sec   = defaultTime_sec;
        this.#remainingTime_sec = remainingTime;

        this.#display(this.#remainingTime_sec);

        this.#cursor = new Cursor();
        this.#cursor.updateCursorPosition(this.#remainingTime_sec, this.#defaultTime_sec);
    }

    getRemainingTime = () => {
        return this.#remainingTime_sec;
    }

    start = () => {
        // endTimeを設定する
        this.#endTime = new Date().getTime() + this.#remainingTime_sec * 1000;

        this.#tick();
    }

    stop = () => {
        clearTimeout(this.#timerId);
    }

    #complete = () => {
        clearTimeout(this.#timerId);

        Livewire.dispatch('timer-completed');
    }

    #tick = () => {
        const currentTime = new Date().getTime();

        this.#remainingTime_sec = Math.round((this.#endTime - currentTime) / 1000);
        
        if (this.#remainingTime_sec <= 0) {
            this.#complete();
            return;
        }

        this.#cursor.updateCursorPosition(this.#remainingTime_sec, this.#defaultTime_sec);
        this.#display(this.#remainingTime_sec);

        this.#timerId = setTimeout(this.#tick, 1000)
    }

    #display = (time) => {
        const timerEl = document.getElementById('countdown');

        const format = (time) => {
            const hours   = Math.floor(time / 3600);
            const minutes = Math.floor((time - hours * 3600) / 60);
            const seconds = time - hours * 3600 - minutes * 60;

            const paddingZero = (times) => times < 10 ? `0${times}` : times;

            return `${paddingZero(hours)}:${paddingZero(minutes)}:${paddingZero(seconds)}`;
        }

        timerEl.innerHTML = format(time);
    }
}