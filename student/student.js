const modal = document.getElementById("modal");
const taskCells = document.querySelectorAll(".active-task");
const taskDiv = document.getElementById("task");

taskCells.forEach(cell => {
    cell.addEventListener("click", async () => {
        const task = cell.innerText;
        taskDiv.innerHTML = task;

        modal.style.display = "block";
    });
});

const closeBtn = document.querySelector(".close");
closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
});
