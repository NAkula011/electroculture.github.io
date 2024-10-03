<?php
  /**
  * Custom PHP Email Form Class Implementation
  */

  class PHP_Email_Form {
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $smtp = [];
    public $messages = [];
    public $ajax = false;

    // Function to add message
    public function add_message($message, $label) {
      $this->messages[] = ['label' => $label, 'message' => $message];
    }

    // Function to send the email
    public function send() {
      $headers = "From: " . $this->from_email . "\r\n";
      $headers .= "Reply-To: " . $this->from_email . "\r\n";
      $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

      $body = "";
      foreach ($this->messages as $msg) {
        $body .= $msg['label'] . ": " . $msg['message'] . "\n";
      }

      return mail($this->to, $this->subject, $body, $headers);
    }
  }

  // Replace with your actual receiving email address
  $receiving_email_address = 'mundhadanm@rknec.edu';

  // Check if the form has been submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {

    // Sanitize and validate the email address
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['error' => 'Invalid email format.']);
      exit;
    }

    // Initialize the PHP_Email_Form
    $contact = new PHP_Email_Form;
    $contact->ajax = true;

    // Set the recipient and sender details
    $contact->to = $receiving_email_address;
    $contact->from_name = $email;
    $contact->from_email = $email;
    $contact->subject = "New Subscription: " . $email;

    // Optional: Uncomment the below code if you want to use SMTP to send emails
    /*
    $contact->smtp = array(
      'host' => 'smtp.example.com',
      'username' => 'your_username',
      'password' => 'your_password',
      'port' => '587'
    );
    */

    // Add the email message
    $contact->add_message($email, 'Email');

    // Send the email and handle the response
    if ($contact->send()) {
      echo json_encode(['success' => 'Your subscription request has been sent successfully!']);
    } else {
      echo json_encode(['error' => 'There was an error sending your subscription request.']);
    }
  } else {
    echo json_encode(['error' => 'Form was not submitted correctly.']);
  }
?>
