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
        setTimeout(() => {
            let content = iframe.contentDocument.body; // Get the content of the iframe

            html2canvas(content, {
                scale: window.devicePixelRatio,  // Adjust for high-DPI screens
                useCORS: true,                   // Allows external images
                letterRendering: true,           // Improves text rendering
                logging: false                   // Disable logging
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                let pdf = new jsPDF("p", "mm", "a4");

                let imgWidth = 210; // A4 width in mm
                let imgHeight = (canvas.height * imgWidth) / canvas.width;

                if (imgHeight > 297) { // A4 height is 297mm
                    pdf = new jsPDF("p", "mm", [imgWidth, imgHeight]);
                }

                pdf.addImage(canvas.toDataURL("image/png"), "PNG", 0, 0, imgWidth, imgHeight);
                pdf.save("Generated_Page.pdf");

                document.body.removeChild(iframe); // Cleanup
            });
        }, 1500); // Wait for the iframe content to load and render
    };

    iframe.src = url; // Load the target page inside the iframe
});
