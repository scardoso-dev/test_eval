<template>
        <bubble-menu class :editor="editor" v-if="editor">
          <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
            
          </button>
      </bubble-menu>
  <div class="editor" v-if="editor" :content="content" :limit="limit" :min-height="min_height" :show-menu-bar="show_menu_bar">
    <!--
    <div class="menu-bar flex flex-row justify-center bg-gray-600" style="min-height:50px">
      <div v-if="show_menu_bar" class="mr-8">


      </div>

    </div>
    -->
    <editor-content class="contentEditor" :editor="editor" :content="editor.getHTML()" :style="'min-height:'+min_height+'px' "/>
    <div v-if="this.limit" class="character-count">
      {{ editor.getCharacterCount() }}/{{ limit }} caractères
    </div>
    <div v-else class="character-count">
      {{ editor.getCharacterCount() }} caractères / Illimité
    </div>
  </div>
</template>

<script>
import { Editor, EditorContent, BubbleMenu } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import CharacterCount from '@tiptap/extension-character-count'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Underline from '@tiptap/extension-underline'
import Document from '@tiptap/extension-document'

export default {
  components: {
    EditorContent, BubbleMenu
  },
  props: {
    content: String,
    limit: Number,
    min_height: Number,
    show_menu_bar: Boolean,
  },
  data() {
    return {
      editor: '',
      limit: this.limit,
      content: this.content,
      show_menu_bar: this.show_menu_bar,
    }
  },

  mounted() {
    this.editor = new Editor({
      type: 'doc',

      editorProps: {
        attributes: {
          class: 'focus:outline-none',
          id: 'editorContent'
        },

      },
      extensions: [
        Document,
        StarterKit,
        CharacterCount.configure({
          limit: this.limit,
        }),
        Underline,
        Text,
        Paragraph,
        Placeholder.configure({
          HTMLAttributes: {
            class:"placeholder"
          }
        })

      ],
      injectCSS: true,
      autofocus: false,
 
    })
    // On injecte le text au chargement si il y a du contenu à être modifié
    if(this.content) {
      // !!! //
      //this.editor.commands.insertContent(this.content)
      // Insert génère un espace lorsque l'on charge un contenu 
      this.editor.commands.setContent(this.content)
    }
    

  
  },

  beforeUnmount() {
    this.editor.destroy()
  },

}
</script>
<style scoped>
  .menu-bar {
    padding: 0.5rem;
  }
  /*
  button + button {
    margin-left: 0.5rem;
  }
  button {
    padding: 0.5rem;
    color: white;
  }
  */
  button::before {
    color: white;
    background-color: darkslategray;
    padding: 3px;
    content: 'visible';
    font-size: 0.8rem;
    text-transform: uppercase;
    border-radius: 9999px;
    font-weight: bold;

  }
  .character-count {
    text-align: right;
    /*margin-top: 1rem;*/
    color: #868e96;
    padding-right: 0.5rem;
  }
  .is-active::before {
    background-color: darkslategray;
    color: white;
    content: 'cacher';
    text-transform: uppercase;
    font-weight: bold;
  }

  button:focus {
    outline: none;
  }
</style>

<style lang="scss">
/* Basic editor styles */
.ProseMirror {
  > * + * {
    
    padding-left: 0.1em;
    border-style:none;
    
  }


  h1 {
    font-size: 2.5rem;
  }
  h2 {
    font-size: 2rem;
  }
  h3 {
    font-size: 1.5rem;
  }
}

.ProseMirror-focused {

border-style:none;
  
}
.ProseMirror {
  padding: 0.5rem;
}

.ProseMirror p.is-editor-empty:first-child::before {
  //content: attr(data-placeholder); // DEFAULT => 'Write Something ...'
  content: 'Écrivez votre texte...';
  float: left;
  color: #ced4da;
  pointer-events: none;
  height: 0;
}
</style>

<style >
strong {
  background-color: green;
  color: white;
  content: 'annuler'
}
</style>