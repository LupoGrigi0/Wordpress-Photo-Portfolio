Project Notes
Cladue sonnet 3.7 is an excellent code assistant, in my experience much better than OpenAI 40
But Cladue has a relatively short context window, which introduces interesting challengs when working on a large project
This project represents a strategy of having 3.7 sonnet (named Genevieve for this project) create a project plan and architecture, then create prompts for each individual file to be created in a seporate chat. 
Each new chat is "blind" to the entire project so initially Genevieve created a base prompt for each file, that I then prepended with extra project context information such as directory structure. 
I also had Genevieve create a summary of each file as Genevieve created them, focusing on what other files in the project would need to know, basiclly a dependancies document just like the AI version of .h file in C
TEMPLATE functions .php. First real file using prompt built by project manager chat and prompt context prompt. Also Claud Sonnet 4 came out and I was given opportunity to use that. For this file cladue asked a good set of questions. 
wholly fuck... the files are big and blow through Cladue's context window FAST. 
For example Cladue needed to know:
    For creating template-functions.php, I need some key details from those other files to make sure everything integrates properly:
    From custom-post-types.php:

    What's the custom post type slug? (probably 'portfolio' but want to confirm)
    What custom fields/meta keys are you using for portfolio items?
    Any custom taxonomies and their slugs?
    What meta boxes or custom fields are set up for storing image data/directory paths?

    From functions.php:

    How are you enqueuing the CSS/JS files? (need to know the handle names)
    Any custom image sizes you've registered?
    What hooks are you using for theme setup?
    Any custom constants or global variables defined?

    Also helpful to know:

    Are you using a specific naming convention for your media directories?
    How do you want to map directory structure to pages? (like /portfolio/sculptures/ maps to a page called "Sculptures"?)
    Any specific meta fields you want for titles/subtitles at the block level?

    Once I have these details, I can craft a solid template-functions.php that'll handle all your dynamic block generation, directory scanning, parallax attributes, and those sweet progressive transparency effects you're after. The floating content blocks with crossfading backgrounds sound like they're going to look absolutely gorgeous! 🎨
so I opened a new chat loaded functions.php and custom-post-types.php and it IMMEDATELY told me I was out of context window. 
SO, opened a new chat, and asked just the questions about functions.php
Then in a new chat asked questions about custom-post-types.php
It answered both questions.. THEN TOLD ME I WAS OUT OF ACTIONS...

So here I am trying to capture context... 
I have _heaveily_ modivied the prompt for template-functions... and waiting until my usage time is available again... I guess I'll go create some artwork for my girlfriend. After I get template-functions completed I need to go have a conversation witht the project manager chat about the fact that these files keep filling up the context window. 

AAAAHHH... when I added functions_php.php billions of errors. mostly because of undefined... @package ArtPortfolioTheme