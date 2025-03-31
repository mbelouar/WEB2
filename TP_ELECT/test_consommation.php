<?php
// This is a simple test page to diagnose consumption submission issues
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Test Consumption API</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <h1>Test Consumption API</h1>
    
    <div class="card mb-4">
      <div class="card-header">Test Simple JSON Response</div>
      <div class="card-body">
        <button id="testJson" class="btn btn-primary mb-3">Test Basic JSON</button>
        <div id="testJsonResult" class="bg-light p-3 border rounded"></div>
      </div>
    </div>
    
    <div class="card mb-4">
      <div class="card-header">Manual Test Form</div>
      <div class="card-body">
        <form id="simpleForm" class="mb-3">
          <div class="mb-3">
            <label for="testMonth" class="form-label">Month</label>
            <input type="text" id="testMonth" name="month" class="form-control" value="2025-03">
          </div>
          <div class="mb-3">
            <label for="testReading" class="form-label">Current Reading</label>
            <input type="number" id="testReading" name="currentReading" class="form-control" value="1000">
          </div>
          <input type="hidden" name="action" value="add">
          <button type="submit" class="btn btn-success">Submit Without Photo</button>
        </form>
        <div id="formResult" class="bg-light p-3 border rounded"></div>
      </div>
    </div>
    
    <div class="card">
      <div class="card-header">Debug Endpoint Test</div>
      <div class="card-body">
        <button id="testDebug" class="btn btn-secondary">Test Debug Endpoint</button>
        <div id="debugResult" class="bg-light p-3 border rounded mt-3" style="max-height: 400px; overflow: auto;"></div>
      </div>
    </div>
  </div>
  
  <script>
    // Test basic JSON functionality
    document.getElementById('testJson').addEventListener('click', async () => {
      const resultDiv = document.getElementById('testJsonResult');
      resultDiv.innerHTML = 'Loading...';
      
      try {
        const response = await fetch('traitement/consommationTraitement.php?test=1');
        const rawText = await response.text();
        
        resultDiv.innerHTML = `<strong>Raw response:</strong><br><pre>${escapeHtml(rawText)}</pre>`;
        
        try {
          const data = JSON.parse(rawText);
          resultDiv.innerHTML += `<strong>Parsed JSON:</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        } catch (e) {
          resultDiv.innerHTML += `<strong>JSON Parse Error:</strong><br><pre>${e.message}</pre>`;
        }
      } catch (e) {
        resultDiv.innerHTML = `<div class="text-danger">Fetch Error: ${e.message}</div>`;
      }
    });
    
    // Test form submission without photo
    document.getElementById('simpleForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const resultDiv = document.getElementById('formResult');
      resultDiv.innerHTML = 'Submitting...';
      
      const formData = new FormData(e.target);
      
      try {
        const response = await fetch('traitement/consommationTraitement.php', {
          method: 'POST',
          body: formData
        });
        
        const rawText = await response.text();
        resultDiv.innerHTML = `<strong>Raw response:</strong><br><pre>${escapeHtml(rawText)}</pre>`;
        
        try {
          const data = JSON.parse(rawText);
          resultDiv.innerHTML += `<strong>Parsed JSON:</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        } catch (e) {
          resultDiv.innerHTML += `<strong>JSON Parse Error:</strong><br><pre>${e.message}</pre>`;
        }
      } catch (e) {
        resultDiv.innerHTML = `<div class="text-danger">Fetch Error: ${e.message}</div>`;
      }
    });
    
    // Test debug endpoint
    document.getElementById('testDebug').addEventListener('click', async () => {
      const resultDiv = document.getElementById('debugResult');
      resultDiv.innerHTML = 'Loading...';
      
      try {
        const response = await fetch('traitement/consommationTraitement.php', {
          method: 'POST',
          body: new URLSearchParams({
            'action': 'debug'
          })
        });
        
        const rawText = await response.text();
        resultDiv.innerHTML = `<strong>Raw response:</strong><br><pre>${escapeHtml(rawText)}</pre>`;
        
        try {
          const data = JSON.parse(rawText);
          resultDiv.innerHTML += `<strong>Parsed JSON:</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        } catch (e) {
          resultDiv.innerHTML += `<strong>JSON Parse Error:</strong><br><pre>${e.message}</pre>`;
        }
      } catch (e) {
        resultDiv.innerHTML = `<div class="text-danger">Fetch Error: ${e.message}</div>`;
      }
    });
    
    // Helper to escape HTML
    function escapeHtml(unsafe) {
      return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }
  </script>
</body>
</html>
