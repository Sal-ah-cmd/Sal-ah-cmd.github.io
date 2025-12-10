const { createApp, ref, reactive } = Vue;

const appOptions = {
    setup() {
        const formData = reactive({
            name: "Sal",
            imagePath: "images/pfp.jpg",
            caption: "a photo of my profile picture",
            statement: "Hello! My name is Sal. I am excited to be taking this course to expand my skills in web development.",
            interest1: "video games",
            interest2: "art",
            interest3: "writing",
            courses: ["WEB215", "WEB250"]
        });

        const newCourseInput = ref('');
        const previewGenerated = ref(false);

        const addCourse = () => {
            const courseValue = newCourseInput.value.trim();
            if (courseValue) {
                formData.courses.push(courseValue);
                newCourseInput.value = '';
            } else {
                alert("Please enter a course name before adding.");
            }
        };

        const removeCourse = (index) => {
            formData.courses.splice(index, 1);
        };
        
        const generatePreview = () => {
            previewGenerated.value = true;
            
            Vue.nextTick(() => {
                const outputDiv = document.getElementById('form-output-area');
                if (outputDiv) {
                    outputDiv.scrollIntoView({ behavior: 'smooth' });
                }
            });
        };

        return {
            formData,
            newCourseInput,
            previewGenerated,
            addCourse,
            removeCourse,
            generatePreview
        };
    }
};

createApp(appOptions).mount('#app');