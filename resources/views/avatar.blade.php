<script>
    window.addEventListener('DOMContentLoaded', () => {
        const avatarImage = document.querySelector("#avatar-img");
        const image = avatarImage.querySelector("img");
        const skeleton = avatarImage.querySelector(".skeleton");

        const removeSkeleton = () => {
            skeleton.style.display = "none";
            image.style.display = "block";
        }

        if (image.complete) removeSkeleton()

        image.onload = removeSkeleton;
    });
</script>

<div id="avatar-img" class="avatar">
    <div class="skeleton"></div>
    <img style="display: none" src="images/avatars/{{$user->avatar ?? "default_user.svg"}}" alt="">
</div>
