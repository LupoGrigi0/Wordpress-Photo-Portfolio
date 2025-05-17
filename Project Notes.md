Project Notes
Cladue sonnet 3.7 is an excellent code assistant, in my experience much better than OpenAI 40
But Cladue has a relatively short context window, which introduces interesting challengs when working on a large project
This project represents a strategy of having 3.7 sonnet (named Genevieve for this project) create a project plan and architecture, then create prompts for each individual file to be created in a seporate chat. 
Each new chat is "blind" to the entire project so initially Genevieve created a base prompt for each file, that I then prepended with extra project context information such as directory structure. 
I also had Genevieve create a summary of each file as Genevieve created them, focusing on what other files in the project would need to know, basiclly a dependancies document just like the AI version of .h file in C