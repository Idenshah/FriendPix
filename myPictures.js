document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".container");

    container.addEventListener("click", (e) => {
        if (e.target.classList.contains("picture")) {
            e.preventDefault();

            // Retrieve the picture id from the data attribute
            const pictureId = e.target.getAttribute("data-picture-id");

            // Main flex container for bigImage and comments
            const Imagecontainer = document.createElement("div");
            Imagecontainer.style.cssText = `
                display: flex;
                flex-direction: row;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                z-index: 1000;
                color: #fff;
                overflow: hidden;
                padding: 30px;
            `;

            // Section for the big image
            const bigImage = document.createElement("div");
            bigImage.style.cssText = `
                flex: 2;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-top:20px;
                padding: 20px;
            `;

            const img = document.createElement("img");
            img.src = e.target.src; // Use the clicked image's src
            img.alt = e.target.alt; // Use the clicked image's alt
            img.style.cssText = `
                max-width: 70%;
                max-height: 70%;
            `;

            const title = document.createElement("div");
            title.textContent = "Title: " + (e.target.title || "No title available");
            title.style.cssText = `
                margin-top: 10px;
                font-weight: bolder;
                font-size: 20px;
             `;

            const description = document.createElement("div");
            description.textContent = "Description:" + " " + e.target.getAttribute("description") || "No description available";
            description.style.marginTop = "5px";

            const closeButton = document.createElement("button");
            closeButton.textContent = "Close";
            closeButton.style.cssText = `
                margin-top: 15px;
                padding: 10px 20px;
                background: #f00;
                color: #fff;
                border: none;
                cursor: pointer;
            `;
            closeButton.addEventListener("click", () => {
                Imagecontainer.remove();
                document.body.style.overflow = ""; // Restore scrolling
            });

            bigImage.appendChild(title);
            bigImage.appendChild(img);
            bigImage.appendChild(description);
            bigImage.appendChild(closeButton);

            // Section for the comments
            const imageComments = document.createElement("div");
            imageComments.style.cssText = `
                flex: 1;
                padding: 20px;
                margin-top: 50px;
                background-color: #f00;
            `;

            const commentsTitle = document.createElement("h2");
            commentsTitle.textContent = "Comments";
            commentsTitle.style.cssText = `
                margin-top: 20px;
                color: #fff;
            `;

            imageComments.appendChild(commentsTitle);

            // Loop through all comments and find those that match the clicked image's ID
            const comments = document.querySelectorAll(".comment");
            comments.forEach(comment => {
                // Check if the comment belongs to the clicked image
                if (comment.getAttribute("data-picture-id") === pictureId) {
                    const commentWrapper = document.createElement("div");
                    commentWrapper.style.cssText = `
                        margin-bottom: 15px;
                        padding: 10px;
                        background-color: #fff;
                        border-radius: 5px;
                    `;

                    const author = document.createElement("strong");
                    author.textContent = comment.getAttribute("data-comment-writer") + ": ";
                    author.style.color = "#00f";

                    const text = document.createElement("span");
                    text.textContent = comment.getAttribute("data-comment-text");
                    text.style.color = "#333";

                    const deleteButton = document.createElement("button");
                    deleteButton.textContent = "Delete";
                    deleteButton.style.cssText = `
                        margin-left: 15px;
                        padding: 5px 10px;
                        background: #f00;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                        border-radius: 3px;
                    `;
                    deleteButton.addEventListener("click", () => {
                        commentWrapper.remove();
                    });

                    commentWrapper.appendChild(author);
                    commentWrapper.appendChild(text);
                    commentWrapper.appendChild(deleteButton);
                    imageComments.appendChild(commentWrapper);
                }
            });

            // Append both sections to the main container
            Imagecontainer.appendChild(bigImage);
            Imagecontainer.appendChild(imageComments);

            // Prevent background scrolling
            document.body.style.overflow = "hidden";

            document.body.appendChild(Imagecontainer);
        }
    });
});
