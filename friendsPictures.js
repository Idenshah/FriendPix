document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".container");

    container.addEventListener("click", (e) => {
        if (e.target.classList.contains("picture")) {
            e.preventDefault();

            const bigImage = document.createElement("div");
            bigImage.classList.add("bigImage");

            // Inline styles could be moved to a separate CSS file
            Object.assign(bigImage.style, {
                position: "fixed",
                top: "0",
                left: "0",
                width: "100%",
                height: "100%",
                backgroundColor: "rgba(0, 0, 0, 0.8)",
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                flexDirection: "column",
                color: "#fff",
                zIndex: "1000",
            });

            const img = document.createElement("img");
            img.src = e.target.src;
            img.alt = e.target.alt || "No alt text";  // Ensure alt is set
            img.style.maxWidth = "40%";
            img.style.maxHeight = "40%";
            bigImage.appendChild(img);

            const title = document.createElement("div");
            title.classList.add("imgTitle");
            title.textContent = "Image Title: " + (e.target.title || "No title available");
            title.style.marginTop = "10px";
            title.style.fontWeight = "bold";
            bigImage.appendChild(title);

            const description = document.createElement("div");
            description.classList.add("imgDescription");
            description.textContent = "Image Description: " + (e.target.getAttribute("description") || "No description available");
            description.style.marginTop = "5px";
            bigImage.appendChild(description);

            const form = document.createElement("form");
            form.classList.add("comment");
            form.method = "post";
            form.action = "FriendsPictures.php";  // Ensure this path is correct

            const pictureId = e.target.getAttribute("data-picture-id");  // Check that this attribute exists

            if (!pictureId) {
                console.error("No picture ID found.");
                return;  // Handle missing picture ID
            }

            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "pictureId";
            hiddenInput.value = pictureId;
            form.appendChild(hiddenInput);

            const commentBox = document.createElement("div");
            const textCommentBox = document.createElement("div");
            const textComment = document.createElement("textarea");
            textComment.classList.add("form-control");
            textComment.rows = 10;
            textComment.cols = 50;
            textComment.name = "imageComment";
            textComment.placeholder = "Write your comment here...";
            textComment.maxLength = 3000;
            textCommentBox.appendChild(textComment);
            commentBox.appendChild(textCommentBox);

            form.appendChild(commentBox);

            const submitButtonBox = document.createElement("div");
            submitButtonBox.classList.add("col-md-4");
            const submitButton = document.createElement("button");
            submitButton.classList.add("btn", "btn-primary");
            submitButton.type = "submit";
            submitButton.textContent = "Comment";
            submitButton.style.borderRadius = "10px";
            submitButton.style.marginTop = "10px";
            submitButtonBox.appendChild(submitButton);
            commentBox.appendChild(submitButtonBox);

            bigImage.appendChild(form);

            bigImage.addEventListener("click", (e) => {
                if (e.target === bigImage) {
                    bigImage.remove();
                }
            });

            document.body.appendChild(bigImage);
        }
    });
});
