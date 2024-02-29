import "./bootstrap";
import initDropZone from "./fileUploadHelpers";

const avatarDoropZone = document.querySelector("#avatar-drop-zone");

const hamMenu = document.querySelector(".ham-menu");
const offScreenMenu = document.querySelector(".off-screen-menu");

if (hamMenu) {
    hamMenu.addEventListener("click", () => {
        hamMenu.classList.toggle("active");
        offScreenMenu.classList.toggle("active");
    });
}

initDropZone(avatarDoropZone);
