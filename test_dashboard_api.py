#!/usr/bin/env python3
import requests
import json
from urllib.parse import urljoin

BASE_URL = "http://localhost/SideKick"

# Create session
session = requests.Session()

# Step 1: Login
print("=== Step 1: Logging in ===")
login_data = {
    "email": "testadmin@test.com",
    "password": "testpass123"
}

login_response = session.post(
    urljoin(BASE_URL, "api/auth/login.php"),
    json=login_data
)

print(f"Login Status: {login_response.status_code}")
if login_response.status_code == 200:
    print(f"Login Response: {json.dumps(login_response.json(), indent=2)}")
else:
    print(f"Login Error: {login_response.text}")
    exit(1)

# Step 2: Call Dashboard API
print("\n=== Step 2: Calling Dashboard API ===")
dashboard_response = session.get(
    urljoin(BASE_URL, "api/dashboard/summary.php")
)

print(f"Dashboard Status: {dashboard_response.status_code}")
if dashboard_response.status_code == 200:
    data = dashboard_response.json()
    print(f"Dashboard Response:\n{json.dumps(data, indent=2)}")
else:
    print(f"Dashboard Error: {dashboard_response.text}")

print("\n=== Test Complete ===")
