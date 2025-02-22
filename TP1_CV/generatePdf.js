document.getElementById("generatePdf").addEventListener("click", function () {
    let url = "cv_gen.php"; // The page you want to generate the PDF from

    // Create an invisible iframe
    let iframe = document.createElement("iframe");
    iframe.style.position = "absolute";
    iframe.style.width = "100%";
    iframe.style.height = "100vh";
    iframe.style.visibility = "hidden";
    document.body.appendChild(iframe);

    iframe.onload = function () {
        // Wait until all resources are fully loaded before proceeding
        let content = iframe.contentDocument || iframe.contentWindow.document;
        
        let images = content.getElementsByTagName("img");
        let loadedImages = 0;
        
        function checkImagesLoaded() {
            loadedImages++;
            if (loadedImages === images.length) {
                generatePDF(content.body);
            }
        }

        if (images.length > 0) {
            for (let img of images) {
                if (img.complete) {
                    checkImagesLoaded();
                } else {
                    img.onload = checkImagesLoaded;
                    img.onerror = checkImagesLoaded;
                }
            }
        } else {
            generatePDF(content.body);
        }
    };

    iframe.src = url; // Load the target page inside the iframe

    function generatePDF(content) {
        setTimeout(() => {
            html2canvas(content, {
                scale: 2, // More stable for high-resolution screens
                useCORS: true,
                letterRendering: true,
                logging: false
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                let pdf = new jsPDF("p", "mm", "a4");

                let imgWidth = 210; // A4 width in mm
                let imgHeight = (canvas.height * imgWidth) / canvas.width;

                if (imgHeight > 297) { // A4 height is 297mm
                    imgHeight = 297; // Ensure it fits within A4
                }

                pdf.addImage(canvas.toDataURL("image/png"), "PNG", 0, 0, imgWidth, imgHeight);
                pdf.save("Generated_Page.pdf");

                document.body.removeChild(iframe); // Cleanup
            });
        }, 500); // Reduced timeout after images load
    }
});
