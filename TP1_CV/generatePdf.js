document.getElementById("generatePdf").addEventListener("click", function () {
    let url = "cv_gen.php"; // Update this to your target page

    // Create an invisible iframe
    let iframe = document.createElement("iframe");
    iframe.style.position = "absolute";
    iframe.style.width = "100%";
    iframe.style.height = "100vh";
    iframe.style.visibility = "hidden";
    document.body.appendChild(iframe);

    iframe.onload = function () {
        setTimeout(() => {
            let content = iframe.contentDocument.body; // Get iframe content

            html2canvas(content, {
                scale: window.devicePixelRatio,  // Adjusts for high DPI screens
                useCORS: true,                   // Allows external images
                logging: false                   // No console logs
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
        }, 1500); // Wait to ensure full render
    };

    iframe.src = url; // Load the external page
});
