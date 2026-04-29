# actra.domains

## 🚀 Getting Started

Follow these steps to set up the project locally for the first time.

### 1. Prerequisites
Ensure you have [DDEV](https://ddev.readthedocs.io/) installed and running on your machine.

### 2. Installation (One-time)
Clone the repository and run the initial setup script:

```bash
git clone git@github.com:Actra-AG/bsv-buelach.ch.git
cd <project-folder>
bash setup.sh
```

### Start the Environment
To start the project containers (run this every time you start your work day):
```bash
ddev start
```

### 💻 Daily Development
Once your DDEV environment is up and running, you can use our custom utilities at any time to assist with your development tasks.

#### List Registered Users
If you need to verify the users currently stored in the database, you can run the following command directly from your terminal:
```bash
ddev composer list-users
```

### 🛠 Available Commands

| Command | Description                                       |
| :--- |:--------------------------------------------------|
| `ddev start` | Starts the local development environment.         |
| `ddev stop` | Pauses the project and stops containers.          |
| `ddev composer list-users` | Fetches and displays all users from the database. |