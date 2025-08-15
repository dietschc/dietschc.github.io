function generateRandomBlock() {
  const w = Math.floor(Math.random() * window.innerWidth);
  const h = Math.floor(Math.random() * window.innerHeight);
  const s = Math.floor(Math.random() * 20);

  let opacity = 1;
  const container = document.getElementById("page-container");
  const element = document.createElement("div");

  element.classList.add("grid-container");
  element.style.display = "grid";
  element.style.grid = "auto auto auto";
  element.style.position = "absolute";
  element.style.top = h + "px";
  element.style.left = w + "px";
  element.style.transform = `scale(${s}, ${s})`;

  container.appendChild(element);

  // Add children to grid-container
  for (let i = 0; i < 9; i++ ) {
    const rng = Math.floor(Math.random() * 3);
    const child = document.createElement("div");
    element.appendChild(child);

    // Do not display random parts of grid to make shapes
    if (rng % 2 == 0)
      child.style.opacity = 0;
  }

  // Fadeout
  const timer = setInterval((e) => {
    if (opacity <= 0) {
      clearInterval(timer); // Stop the loop
      element.style.display = "none";
    } else {
      opacity -= 0.01;
      element.style.opacity = opacity;
    }
  }, 50); // Delay in ms
}

function makeBlocksContinuously() {
    let i = 0;

    // Poor mans infinite loop
  const intervalId = setInterval(() => {
    if (i >= 100) { // make 100 shapes why not
        clearInterval(intervalId); // Stop the loop
    } else {
        generateRandomBlock();
        // i++; // forever
    }
  }, 50); // Delay in ms
}

// Run this automatically
window.onload = makeBlocksContinuously();

