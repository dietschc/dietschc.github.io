function generateRandomBlock() {
  const w = Math.floor(Math.random() * screen.width);
  const h = Math.floor(Math.random() * screen.height);
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

    // Essentially - Do not display random parts of grid to make shapes
    if (rng % 2 == 0)
      child.style.opacity = 0;

  }

  // Fadout function ->
  // Timer function
    const timer = setInterval((e) => {
    if (opacity <= 0) {
      clearInterval(timer); // Stop the loop
      element.style.display = "none";
    } else {
      opacity -= 0.01;
      element.style.opacity = opacity;
    }
  }, 200); // Delay in ms
}

function makeBlocksContinuosly() {
    let i = 0;

    // Timer function
    const intervalId = setInterval(() => {
    if (i >= 100) { // make 100 shapes why not
        clearInterval(intervalId); // Stop the loop
    } else {
        generateRandomBlock();
        i++;
    }
    }, 5000); // Delay in ms
}

// Run this automatically
window.onload = makeBlocksContinuosly();

