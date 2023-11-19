export class BreakTimeCountdown {
    #remainingTime;
    #intervalId;
    
    constructor(breakTime) {
        this.#remainingTime = breakTime;

        this.#display(this.#remainingTime);
        
        this.#intervalId = setInterval(() => this.#tick(), 1000);
    }

    finish = () => clearInterval(this.#intervalId);

    #tick = () => {    
        if (this.#remainingTime <= 0) {      
            return;
        }

        this.#remainingTime--;
        
        this.#display(this.#remainingTime); 
    }

    #display = (time_sec) => {
        const timerEl = document.getElementById('breakTime-countdown');

        timerEl.setAttribute("remainingTime", this.#remainingTime);

        const format = (time_sec) => {
            const minutes = Math.floor(time_sec / 60);
            const seconds = time_sec % 60;
        
            const paddingZero = (time) => time < 10 ? `0${time}` : time;
        
            return `${paddingZero(minutes)}:${paddingZero(seconds)}`;
        }

        timerEl.innerHTML = format(time_sec);
    }
}