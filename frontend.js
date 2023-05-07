import React, { useState, useEffect } from 'react';
import axios from 'axios';
function App() {
  const [tutors, setTutors] = useState([]);
  const [selectedTutor, setSelectedTutor] = useState(null);
  useEffect(() => {
    axios.get('/api/tutors')
      .then(response => {
        setTutors(response.data);
      })
      .catch(error => {
        console.error(error);
      });
  }, []);

  const handleTutorSelect = (tutor) => {
    setSelectedTutor(tutor);
  }
  return (
    <div>
      <header>
        <h1>Welcome to LearnHub</h1>
        <nav>
          <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#tutors">Tutors</a></li>
            <li><a href="#resources">Resources</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </nav>
      </header>
      <main>
        <section id="home">
          <h2>Find a tutor and start learning today!</h2>
          <p>Our expert tutors are available to provide personalized instruction in a wide range of subjects.</p>
          <button>Get Started</button>
        </section>
        <section id="tutors">
          <h2>Our Tutors</h2>
          <ul>
            {tutors.map(tutor => (
              <li key={tutor._id} onClick={() => handleTutorSelect(tutor)}>
                <h3>{tutor.name}</h3>
                <p>{tutor.bio}</p>
              </li>
            ))}
          </ul>
          {selectedTutor && (
            <div>
              <h3>{selectedTutor.name}</h3>
              <p>{selectedTutor.bio}</p>
              <button>Book a Session</button>
            </div>
          )}
        </section>
        <section id="resources">
          <h2>Learning Resources</h2>
          <p>Access our repository of learning resources to supplement your personalized instruction.</p>
          <ul>
            <li><a href="#">Video Lectures</a></li>
            <li><a href="#">Practice Quizzes</a></li>
            <li><a href="#">Interactive Assignments</a></li>
          </ul>
        </section>
        <section id="contact">
          <h2>Contact Us</h2>
          <p>Have a question or comment? Get in touch with our support team.</p>
          <form>
            <div>
              <label htmlFor="name">Name:</label>
              <input type="text" id="name" name="name" />
            </div>
            <div>
              <label htmlFor="email">Email:</label>
              <input type="email" id="email" name="email" />
            </div>
            <div>
              <label htmlFor="message">Message:</label>
              <textarea id="message" name="message"></textarea>
            </div>
            <button>Send</button>
          </form>
        </section>
      </main>
      <footer>
        <p>&copy; LearnHub 2023</p>
      </footer>
    </div>
  );
}


export default App;
