<?php
session_start();
// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    // Assuming you have a function to fetch user details by ID (you should implement this)
    include('./conn/db_connection.php');  // Include your database connection file
    $query = "SELECT name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if ($user_id) {
    // Include your database connection
    include('./conn/db_connection.php');

    // Fetch groups associated with the logged-in user
    $query = "SELECT group_id, group_name FROM `groups` WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $groups = [];
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;  // Store each group in the array
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Chaos - Start</title>
    <div id="loader-placeholder"></div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    
    <style>
        .hidden { display: none; }

        body {
            margin: 0;
            height: 100vh;
            background-image: url('./images/gradient.png');
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(5px);
            color: #1A1A2E;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
        }

        h1 {
            color: black;
            margin-bottom: 20px;
        }

        .form-label, .btn {
            color: #1A1A2E;
        }

        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
        }

        .cssbuttons-io-button {
            background: rgba(0, 123, 255, 1);
            color: white;
            font-family: inherit;
            padding: 0.35em;
            padding-left: 1.2em;
            font-size: 17px;
            font-weight: 500;
            border-radius: 0.9em;
            border: none;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 1.6em -0.6em #714da6;
            overflow: hidden;
            position: relative;
            height: 2.8em;
            padding-right: 3.3em;
            cursor: pointer;
            margin-left: 1em;
        }
        
        .cssbuttons-io-button .icon {
            background: white;
            margin-left: 1em;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.2em;
            width: 2.2em;
            border-radius: 0.7em;
            box-shadow: 0.1em 0.1em 0.6em 0.2em rgba(0, 123, 255, 1);
            right: 0.3em;
            transition: all 0.3s;
        }
        
        .cssbuttons-io-button:hover .icon {
            width: calc(100% - 0.6em);
        }
        
        .cssbuttons-io-button .icon svg {
            width: 1.8em;
            transition: transform 0.3s;
            color: #7b52b9;
        }
        
        .cssbuttons-io-button:hover .icon svg {
            transform: translateX(0.1em);
        }
        
        .cssbuttons-io-button:active .icon {
            transform: scale(0.95);
        }

        /* New styles for logged-in user features */
        .user-features {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
        
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .groups-icon {
            margin-left: 15px;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
        }

        .group-item:hover {
            background-color: #f8f9fa;
        }

        .group-item:last-child {
            border-bottom: none;
        }

        .member-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .member-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<script>
    // Fetch and include loader.html content
    fetch('loader.html')
      .then(response => response.text())
      .then(data => document.getElementById('loader-placeholder').innerHTML = data);
  </script>


  <script>
// loader.js
window.onload = function() {
  // Show the loader for 3 seconds
  setTimeout(function() {
    // Hide the loader after 3 seconds
    document.querySelector('.loader-container').style.display = 'none';
    
    // Show the main page content or redirect to another page
    document.querySelector('.content').style.display = 'block';
    
    // Or if you want to redirect to another page:
    // window.location.href = "your-main-page.html";
  }, 1000); // 3000ms = 3 seconds
};

</script>


<?php if (isset($_SESSION['user_id'])): ?>  
    <a class="btn btn-primary back-button" href="./logout.php">logout</a>
    <?php endif; ?> 

    <?php if ($user_id): ?>
    <div class="user-features">
        <div class="profile-icon" onclick="openProfileModal()">
            <i class="fas fa-user"></i>
        </div>
        <div class="groups-icon" onclick="openGroupsModal()">
            <i class="fas fa-users"></i>
        </div>
    </div>
    <?php endif; ?>

    <div class="container">
        <h1 class="text-center">Select the Number of Players</h1>
        <div id="startScreen">
            <div class="mb-3">
                <label for="playerCount" class="form-label">(minimum 4):</label>
                <input type="number" class="form-control" id="playerCount" min="4" value="">
            </div>

            <button class="cssbuttons-io-button" id="startGame">
                Start Game
                <div class="icon">
                  <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
                  </svg>
                </div>
            </button>
        </div>
    </div>

    <!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateProfileForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateProfile()">Update</button>
            </div>
        </div>
    </div>
</div>

    <!-- Groups Modal -->
    <div class="modal fade" id="groupsModal" tabindex="-1" aria-labelledby="groupsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupsModalLabel">Your Groups</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="groupsList">
                        <!-- Group items will be dynamically added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Group Details Modal -->
<div class="modal fade" id="groupDetailsModal" tabindex="-1" aria-labelledby="groupDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupDetailsModalLabel">Group Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="groupMembersList">
                    <!-- Table for group members and scores will be dynamically added here -->
                </div>
            </div>
        </div>
    </div>
</div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/start.js"></script>
    <script>
        function openProfileModal() {
            $('#profileModal').modal('show');
        }

        function openGroupsModal() {  
   $('#groupsModal').modal('show');  
  
   // Fetch real groups using AJAX  
   $.ajax({  
      url: 'fetch_groups.php',  
      method: 'GET',  
      success: function(response) {  
        console.log('Response:', response);  
        try {  
           const groups = JSON.parse(response);  
           console.log('Groups:', groups);  
           const groupsList = document.getElementById('groupsList');  
           groupsList.innerHTML = '';  
            
           if (groups.length === 0) {  
              groupsList.innerHTML = '<p>No groups found.</p>';  
           }  
  
           groups.forEach(group => {  
              const groupItem = document.createElement('div');  
              groupItem.className = 'group-item';  
              groupItem.innerHTML = `  
                <span>${group.group_name}</span>  
                <i class="fas fa-trash-alt delete-group" data-group-id="${group.group_id}"></i>  
              `;  
              groupItem.onclick = () => openGroupDetails(group.group_id);  
              groupsList.appendChild(groupItem);  
           });  
  
           // Add event listener to delete group icons  
           const deleteGroupIcons = document.querySelectorAll('.delete-group');  
           deleteGroupIcons.forEach(icon => {  
              icon.onclick = (e) => {  
                e.stopPropagation(); // Prevent opening group details modal  
                const groupId = icon.getAttribute('data-group-id');  
                deleteGroup(groupId);  
              };  
           });  
        } catch (error) {  
           console.error('Error parsing response:', error);  
        }  
      },  
      error: function(xhr, status, error) {  
        console.error('Error fetching groups:', error);  
        console.log('XHR:', xhr);  
        console.log('Status:', status);  
      }  
   });  
}  


function deleteGroup(groupId) {  
   if (confirm('Are you sure you want to delete this group?')) {  
      // Send AJAX request to delete the group  
      $.ajax({  
        url: 'delete_group.php',  
        method: 'POST',  
        data: { group_id: groupId },  
        success: function(response) {  
           console.log('Response:', response);  
           if (response === 'success') {  
              alert('Group deleted successfully');  
              openGroupsModal(); // Refresh groups list  
           } else {  
              alert('Error deleting group: ' + response);  
           }  
        },  
        error: function(xhr, status, error) {  
           console.error('Error deleting group:', error);  
           console.log('XHR:', xhr);  
           console.log('Status:', status);  
        }  
      });  
   }  
}



function openGroupDetails(groupId) {  
   $('#groupsModal').modal('hide');  
   $('#groupDetailsModal').modal('show');  
   
   // Fetch group members using AJAX  
   $.ajax({  
      url: 'fetch_group_members.php',  
      method: 'POST',  
      data: { group_id: groupId },  
      success: function(response) {  
        console.log('Response:', response);  
        try {  
           const members = JSON.parse(response);  
           console.log('Members:', members);  
           const groupMembersList = document.getElementById('groupMembersList');  
           groupMembersList.innerHTML = '';  
            
           if (members.length === 0) {  
              groupMembersList.innerHTML = '<p>No members found.</p>';  
              return;
           }  
            
           // Create table for members and scores  
           const table = document.createElement('table');  
           table.className = 'table';  
           table.innerHTML = `  
              <thead>  
                <tr>  
                  <th>Member Name</th>  
                  <th>Score</th>  
                </tr>  
              </thead>  
              <tbody></tbody>  
           `;  
           const tableBody = table.querySelector('tbody');  
            
           members.forEach(member => {  
              const row = document.createElement('tr');  
              row.innerHTML = `  
                <td>${member.member_name}</td>  
                <td>${member.score || 'N/A'}</td>  
              `;  
              tableBody.appendChild(row);  
           });  
            
           groupMembersList.appendChild(table);  
        } catch (error) {  
           console.error('Error parsing response:', error);  
        }  
      },  
      error: function(xhr, status, error) {  
        console.error('Error fetching group members:', error);  
        console.log('XHR:', xhr);  
        console.log('Status:', status);  
      }  
   });  
}


        function updateProfile() {
    const username = document.getElementById('username').value;
    // Send AJAX request to update the profile (replace with actual AJAX code)
    $.ajax({
        type: 'POST',
        url: 'update_profile.php',  // Create a PHP file to handle updates
        data: { username: username },
        success: function(response) {
            alert('Profile updated successfully');
            $('#profileModal').modal('hide');
        },
        error: function(error) {
            alert('Error updating profile');
        }
    });
}

    </script>
</body>
</html>