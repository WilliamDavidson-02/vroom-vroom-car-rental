let dropZone;

const initDropZone = (element) => {
    if (!element) return;

    dropZone = element;

    dropZone.ondrop = onDrop;
    dropZone.ondragenter = onDragEnter;
    dropZone.ondragleave = onDragLeave;
    dropZone.ondragover = (ev) => ev.preventDefault();

    // Add event listener for none drop upload
    dropZone.onchange = onUpload;
};

const createPlaceholderAvatar = (file) => {
    const avatar = dropZone.querySelector("#avatar-img");
    const image = avatar.querySelector("img");
    const skeleton = avatar.querySelector(".skeleton");

    // Show skeleton until new image is loaded
    skeleton.style.display = "block";
    image.style.display = "none";

    image.src = URL.createObjectURL(file);

    image.onload = () => {
        skeleton.style.display = "none";
        image.style.display = "block";
    };
};

const onDragEnter = (ev) => {
    ev.preventDefault();
    dropZone.classList.add("drop-zone-active");
};

const onDragLeave = (ev) => {
    ev.preventDefault();
    dropZone.classList.remove("drop-zone-active");
};

const onDrop = (ev) => {
    ev.preventDefault();
    dropZone.classList.remove("drop-zone-active");

    if (ev.dataTransfer.files && ev.dataTransfer.files[0]) {
        // Get the file input
        const fileInput = dropZone.querySelector("input");

        fileInput.files = ev.dataTransfer.files;

        createPlaceholderAvatar(ev.dataTransfer.files[0]);

        ev.dataTransfer.clearData();
    }
};

const onUpload = (ev) => {
    if (!ev.target.files.length) return;

    const file = ev.target.files[0];

    createPlaceholderAvatar(file);
};

export default initDropZone;
