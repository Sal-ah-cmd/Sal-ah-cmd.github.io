<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sal's Sneaky Snake — WEB215 — Introduction Form</title>
  <link rel="stylesheet" href="styles/style.css"> 
  <link rel="icon" type="image/x-icon" href="images/snake_favicon.png">
</head>
<body>
  <?php include __DIR__ . '/components/header.php'; ?>
  <main>
    <div id="app"> <h2>Introduction Form</h2>
      
      <form @submit.prevent="generatePreview">
        
        <fieldset>
          <legend>Personal Information</legend>
          
          <div class="form-group">
            <label for="name">Name (e.g., Sal):</label>
            <input type="text" id="name" v-model="formData.name" required>
          </div>
          <div class="form-group">
            <label for="statement">Introductory Statement:</label>
            <textarea id="statement" v-model="formData.statement" rows="3" required></textarea>
          </div>
        </fieldset>
        
        <fieldset>
          <legend>Image Details</legend>
          <div class="form-group">
            <label for="imagePath">Image URL/Path (Source of `src`):</label>
            <input type="text" id="imagePath" v-model="formData.imagePath" required>
          </div>
          <div class="form-group">
            <label for="caption">Image Alt Text (Content of `alt`):</label>
            <input type="text" id="caption" v-model="formData.caption" required>
          </div>
        </fieldset>

        <fieldset>
          <legend>Interests</legend>
          <p>Enter your three main interests:</p>
          <div class="form-group-triple">
            <div class="input-pair">
              <label for="interest_1">Interest 1:</label>
              <input type="text" id="interest_1" v-model="formData.interest1" required>
            </div>
            <div class="input-pair">
              <label for="interest_2">Interest 2:</label>
              <input type="text" id="interest_2" v-model="formData.interest2" required>
            </div>
            <div class="input-pair">
              <label for="interest_3">Interest 3:</label>
              <input type="text" id="interest_3" v-model="formData.interest3" required>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Current Courses</legend>
          <p>Add or remove courses as needed:</p>
          <div id="course-list">
            <div class="course-item-group" v-for="(course, index) in formData.courses" :key="index">
                <input type="text" class="course-input" v-model="formData.courses[index]" required>
                <button type="button" class="remove-course-btn" @click="removeCourse(index)">Remove</button>
            </div>
          </div>
          <div id="add-course-controls">
            <input type="text" v-model="newCourseInput" placeholder="example WEB115">
            <button type="button" @click="addCourse">Add Course</button>
          </div>
        </fieldset>
        
        <button type="submit">Generate Preview</button>
      </form>
      
      <hr>
      
      <div id="form-output-area" v-if="previewGenerated">
        <h3>Introduction</h3>
        
        <img :src="formData.imagePath" :alt="formData.caption" style="max-width: 200px; height: auto;">
        
        <p>{{ formData.statement.replace('Sal', formData.name) }}</p>

        <ul>
          <li>My interests includes {{ formData.interest1 }}, {{ formData.interest2 }}, and a bit of {{ formData.interest3 }}.</li>
          
          <li v-if="formData.courses.length > 0">
            I am currently taking the following courses:
            <ol>
              <li v-for="course in formData.courses">{{ course }}</li>
            </ol>
          </li>
        </ul>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
  
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script src="scripts/introduction.js"></script>
  <script src="scripts/HTMLInclude.js"></script>
</body>
</html>