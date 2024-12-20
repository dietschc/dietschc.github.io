function generateRandomBlock() {
  const maxWidth = screen.width;
  const maxHeight = screen.height;
  const w = Math.floor(Math.random() * (maxWidth / 2));
  const h = Math.floor(Math.random() * (maxHeight / 2));
  const s = Math.floor(Math.random() * 5);
  console.log("random scale:", s);

  let opacity = 1;
  const container = document.getElementById("page-container");
  const element = document.createElement("div");
  container.appendChild(element);

  element.classList.add("randomBox");
  element.style.display = "flex";
  element.style.position = "absolute";
  element.style.top = w + "px";
  element.style.left = h + "px";
  element.style.transform = `scale(${s}, ${s})`;

  // Timer function
    const timer = setInterval((e) => {
    if (opacity <= 0) {
      clearInterval(timer); // Stop the loop
      element.style.display = "none";
    } else {
      opacity -= 0.05;
      element.style.opacity = opacity;
    }
  }, 100); // Delay in ms
}

function makeBlocksContinuosly() {
    let i = 0;

    // Timer function
    const intervalId = setInterval(() => {
    if (i >= 10) {
        clearInterval(intervalId); // Stop the loop
    } else {
        console.log("Iteration:", i);
        generateRandomBlock();
        i++;
    }
    }, 5000); // Delay in ms
}

window.onload = makeBlocksContinuosly();

