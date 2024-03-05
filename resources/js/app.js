import "./bootstrap";
import initDropZone from "./fileUploadHelpers";

const dropZones = document.querySelectorAll("#image-dropzone");

const hamMenu = document.querySelector(".ham-menu");
const offScreenMenu = document.querySelector(".off-screen-menu");

if (hamMenu) {
    hamMenu.addEventListener("click", () => {
        hamMenu.classList.toggle("active");
        offScreenMenu.classList.toggle("active");
    });
}

window.addEventListener("DOMContentLoaded", () => {
    // Handle all images that contains a skeleton loading state
    const imageContaienrs = document.querySelectorAll("#image-container");

    if (imageContaienrs) {
        imageContaienrs.forEach((imageContainer) => {
            const image = imageContainer.querySelector("img");
            const skeleton = imageContainer.querySelector(".skeleton");

            if (image.complete) skeleton.style.display = "none";

            image.onload = skeleton.style.display = "none";
        });
    }
});

if (dropZones) {
    dropZones.forEach((dropZone) => initDropZone(dropZone));
}
