<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comment</title>
<script>
  MathJax = {
    tex: {
      inlineMath: [['$', '$'], ['\\(', '\\)']],
      displayMath: [['$$', '$$'], ['\\[', '\\]']]
    }
  };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<style>
  body, input, textarea, button {
    font-family: "Times New Roman", Times, serif;
    font-size: 18px;
  }
  .form-group {
    margin-bottom: 12px;
  }
  label {
    display: block;
    margin-bottom: 4px;
    font-weight: bold;
  }
  input[type="text"] {
    width: 300px;
    padding: 6px;
    font-size: 16px;
  }
  textarea {
    width: 100%;
    max-width: 600px;
    height: 140px;
    padding: 8px;
    font-size: 16px;
    resize: vertical;
  }
  input[type="submit"] {
    padding: 6px 16px;
    font-size: 16px;
    cursor: pointer;
  }
</style>
</head>
<body style="font-size: 18px;">
<h2>Comments (한글 가능)</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  <div class="form-group">
    <label for="username">Name:</label>
    <input type="text" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="comment">Contents:</label>
    <textarea id="comment" name="comment" required></textarea>
  </div>
  <div class="form-group">
    <input type="submit" value="Enter">
  </div>
</form> 
<hr>
<div id="comments">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username']);
	$comment = trim($_POST['comment']);
	if (!empty($username) && !empty($comment)) {
		$time = date('Y-m-d H:i:s');
		$comment_data = htmlspecialchars($username) . '|' . htmlspecialchars($comment) . '|' . $time;
		$existing_comments = [];
		if (file_exists('comments.txt')) {
			$existing_comments = file('comments.txt', FILE_IGNORE_NEW_LINES);
		}
		array_unshift($existing_comments, $comment_data);
		if (file_put_contents('comments.txt', implode("\n", $existing_comments))) {}
		else { echo "Fail!"; }
		header("Location: " . $_SERVER['PHP_SELF']);
		exit;
	} else { echo "Enter all fields."; }
}
if (file_exists('comments.txt')) {
	$comments = file('comments.txt');
	foreach ($comments as $comment) {
		list($username, $text, $time) = explode('|', $comment, 3);
		if (strpos($username, "hidden-") !== false) {
			list($no, $hiddenname) = explode('-', $username, 2);
			echo '<div><details><summary> This comment has been hidden. </summary>';
			echo "<div><strong>" . htmlspecialchars($hiddenname) . "</strong> (" . $time . ")<br>";
			echo nl2br($text);
			echo "</details></div><hr>";
		} else {
			echo "<div><strong>" . htmlspecialchars($username) . "</strong> (" . $time . ")<br>";
			echo nl2br($text) . "</div><hr>";
		}
	}
}
?>
</div>
</body>
</html>
