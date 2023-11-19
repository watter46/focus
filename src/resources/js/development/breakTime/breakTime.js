import { BreakTimeCountdown } from "./breakTimeCountdown";

window.addEventListener("livewire:init", () => {    
    let timer;
    
    window.startBreakTime  = (breakTime_sec) => timer = new BreakTimeCountdown(breakTime_sec);
    window.finishBreakTime = () => timer.finish();
});